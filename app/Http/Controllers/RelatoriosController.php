<?php

namespace App\Http\Controllers;

use App\Consumers;
use App\Locais;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Categorias;
use App\Valores;
use App\Vendas;
use Exception;

class RelatoriosController extends Controller
{

    public function show(){
        $user = Auth::user();
        if($user->level < 3){
            return abort(403, 'Você não é administrador');
        }
        $consumers = Consumers::where('status', 1)->get();
        foreach($consumers as $key => $consumer){
            $consumer->cpf = $this->formatCnpjCpf($consumer->cpf);
        }
        $categs = Categorias::where('status', 1)->get();
        $categorias = array();
        foreach($categs as $key => $categoria){
            $categorias[$key]['id'] = encrypt($categoria->id);
            $categorias[$key]['nome'] = $categoria->name.' - '.$categoria->description;
        }
        return view('relatorios.relatorios', compact('consumers', 'categorias'));
    }
    function formatCnpjCpf($value){
        $cnpj_cpf = preg_replace("/\D/", '', $value);
        if (strlen($cnpj_cpf) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        }
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }
    public function generate(Request $request){
        $user = Auth::user();
        //dd($request->all());
        if ($user->level >= Valores::ADMINISTRADOR){
            $this->validate($request, [
                'filtroPor' => 'required',
                'cliente' => 'required',
                'categoria' => 'required',
                'tipo' => 'required'
            ],[
                'filtroPor' => 'O campo de data do filtro é obrigatório',
                'tipo.required' => 'O campo de data do filtro é obrigatório',
                'cliente.required' => 'O campo de cliente/consumidor é obrigatório',
                'categoria.required' => 'O campo de categoria é obrigatório'
            ]);
            date_default_timezone_set('America/Bahia');
            $date = new DateTime();
            $data = $date->format('Y-m-d-H:i:s');
            $rows = '';
            try{
                $clienteId = decrypt($request->cliente);
                $categoriaId = decrypt($request->categoria);
            }
            catch(\Exception $e){
                return redirect()->route('relatorios')->withErrors(['categoria.required' => 'Hash inválida do campo de clientes/categoria']);
            }
                        
            switch($request->filtroPor){
                case Valores::DIA:
                    $day = Carbon::parse($request->day)->format('Y-m-d');
                    $datafilterI = $day.' '.'00:00:00';
                    $datafilterF = $day.' '.'23:59:59';
                    $vendas = $this->vendas($clienteId, $categoriaId, $user, $datafilterI, $datafilterF);
                    
                break;
                case Valores::MES:
                    $month = Carbon::parse($request->month)->format('Y-m');
                    $datafilterI = $month.'-01'.' '.'00:00:00';
                    $datafilterF = $month.'-31'.' '.'23:59:59';
                    $vendas = $this->vendas($clienteId, $categoriaId, $user, $datafilterI, $datafilterF);
                    
                break;
                case Valores::ANO:
                    $year = Carbon::parse($request->year)->format('Y');
                    $datafilterI = $year.'-01-01'.' '.'00:00:00';
                    $datafilterF = $year.'-12-31'.' '.'23:59:59';
                    $vendas = $this->vendas($clienteId, $categoriaId, $user, $datafilterI, $datafilterF);
                break;
                default:
                    return redirect()>route('relatorios')->with('error', 'Período Inválido');
                break;
            }
            $valorTotal = $this->sumColumn($vendas, 'valor');
            foreach($vendas as $relato){
                try{
                    if($relato->tipo == Valores::CADASTRADO){
                        $relato['consumer'] = Consumers::find($relato->consumer_id)->name;
                    }
                    elseif($relato->tipo == Valores::NAOCADASTRADO){
                        $relato['consumer'] = $relato->consumer_name;
                    }
                    else{
                        throw new Exception('Consumer Null');
                    }
                }
                catch(\Exception $e){
                    $relato['consumer'] = 'Inválido';
                }
                /*******************/
                try{
                    $relato->local_id = Locais::find($relato->local_id)->name;
                }
                catch(\Exception $e){
                    $relato->local_id = 'Inválido';
                }
                /*******************/
                try{
                    $relato['categ'] = $relato->categoria->name.' - '.$relato->categoria->description;
                }
                catch(\Exception $e){
                    $relato['categ'] = 'Inválida';
                }
                /*******************/
                switch($relato->forma){
                    case Valores::QUANT:
                        $relato->forma = 'Quantidade';
                    break;
                    case Valores::APAGAR:
                        $relato->forma = 'A Pagar';
                    break;
                    default:
                        $relato->forma = 'Inválido';
                    break;
                }
                /*******************/
                
            }
            if($request->tipo == Valores::VENDAS){
                foreach ($vendas as $relato){
                $rows .= '
                        <tr>
                            <th>'.$relato->id.'</th>
                            <td>'.$relato->consumer_id.'</td>
                            <td>'.$relato->local_id.'</td>
                            <td>'.$relato->categoria_id.'</td>
                            <td>'.$relato->forma.'</td>
                            <td>'.$relato->valor.'</td>
                            <td>'.$relato->status.'</td>
                            <td>'.Carbon::parse($relato->created_at)->format('d-m-Y H:i:s').'</td>
                        </tr>';
                }
                $html = '<table>';
                
                $html .=
                    '<thead>
                    <tr>
                        <td>CÓDIGO</td>
                        <td>CONSUMIDOR</td>
                        <td>LOCAL</td>
                        <td>CATEGORIA</td>
                        <td>FORMA</td>
                        <td>VALOR</td>
                        <td>STATUS</td>
                        <td>DATA</td>
                    </tr>
                </thead>';
                $html .=
                    '<tbody>'.$rows.'</tbody>';
                $html .=
                    '<tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total de Itens: </td>
                            <td>'.count($vendas).'</td>
                    </tr>';
                $html .=
                    '<tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Valor Total: </td>
                            <td>R$ '.$valorTotal.'</td>
                    </tr>';
                $html .= '</table>';
            }
            elseif($request->tipo == Valores::DADOS){
                if($categoriaId == 0){
                    $categorias = Categorias::all();
                }
                else{
                    $categorias = Categorias::where('id', $categoriaId)->get();
                }
    
                $space = '<tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>'; 
    
                $lineCategs = '';    
                $totalUse = 0;
                $totalNotUse = 0;
                $total = 0;
                foreach($categorias as $categoria){
                    $categcrt = $categoria->crt
                            ->where('created_at', '>=', $datafilterI)
                            ->where('created_at', '<=', $datafilterF);
                    $categvendas = $categoria->vendas
                            ->where('created_at', '>=', $datafilterI)
                            ->where('created_at', '<=', $datafilterF);
                    $lineCategs .= '
                            <tr>
                                <td>'.$categoria->name.' - '.$categoria->description.'</td>
                                <td>'.$categcrt->count().'</td>
                                <td>'.($categcrt->count() - $categvendas->count()).'</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>';

                    $totalUse = $totalUse + $categcrt->count();
                    $totalNotUse = $totalNotUse + ($categcrt->count() - $categvendas->count());
                    $total = $total + $categvendas->count();
                } 
                $html = '<table>';
                $html .= '<tr>
                            <td>Categorias</td>
                            <td>Usados</td>
                            <td>Não Usados</td>
                            <td>Total</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>'; 
                $html .= $lineCategs;
                $html .= '<tr>
                            <td>Total</td>
                            <td>'.$totalUse.'</td>
                            <td>'.$totalNotUse.'</td>
                            <td>'.$total.'</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>';
                $html .= $space;
                $html .= $space;
            }
            dd($html);
            header('Content-Transfer-Encoding: binary');
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=Relatorio".$data.".xls");

            echo chr(255).chr(254).iconv("UTF-8", "UTF-16LE//IGNORE", $html);

        }
        else{
            return abort(403, 'Error de permissão !!');
        }
    }

    protected function vendas($clienteId, $categoriaId, $user, $datafilterI, $datafilterF){
        if($clienteId == 0){
            if($categoriaId == 0){
                $vendas = Vendas::where('local_id', $user->local_id)
                                ->where('created_at', '>=', $datafilterI)
                                ->where('created_at', '<=', $datafilterF)->get();
            }
            else{
                $vendas = Vendas::where('local_id', $user->local_id)
                                    ->where('categoria_id', $categoriaId)
                                    ->where('created_at', '>=', $datafilterI)
                                    ->where('created_at', '<=', $datafilterF)->get();
            }
        }
        else{
            if($categoriaId == 0){
                $vendas = Vendas::where('local_id', $user->local_id)
                                ->whereIn('consumer_id', $clienteId)
                                ->where('created_at', '>=', $datafilterI)
                                ->where('created_at', '<=', $datafilterF)->get();
            }
            else{
                $vendas = Vendas::where('local_id', $user->local_id)
                                ->where('categoria_id', $categoriaId)
                                ->whereIn('consumer_id', $clienteId)
                                ->where('created_at', '>=', $datafilterI)
                                ->where('created_at', '<=', $datafilterF)->get();
            }
        }
        return $vendas;
    }
    protected function sumColumn($array, $column){
        $soma = 0;
        foreach($array as $a){
            $soma = $soma+$a->$column;
        }
        return $soma;
    }
}
