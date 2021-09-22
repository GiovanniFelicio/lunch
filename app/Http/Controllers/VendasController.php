<?php

namespace App\Http\Controllers;

use App\Categorias;
use App\UsersActions;
use App\Consumers;
use App\Vendas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Valores;
use Exception;
use Yajra\DataTables\DataTables;

class VendasController extends Controller
{
    public function show(){
        return view('vendas.vendas');
    }
    public function adicionar(){
        $user = Auth::user();
        if($user->level < Valores::ADMINISTRADOR){
            return abort(403, 'Você não é administrador');
        }
        $consumers = Consumers::where('status', 1)->get(['id', 'name', 'cpf']);
        foreach($consumers as $key => $consumer){
            $consumer->cpf = $this->formatCnpjCpf($consumer->cpf);
            $consumer['reference'] = encrypt($consumer->id);
        }
        return view('vendas.adicionar', compact('consumers'));
    }
    public function getdatavendas(){
        $user = Auth::user();
        $vendas = Vendas::where('local_id', $user->local_id)->get();
        foreach($vendas as $venda){
            try{
                $venda['consumidor'] = $venda->consumer->name ?? $venda->consumer_name;
            }
            catch(\Exception $e){
                $venda['consumidor'] = 'Nome Inválido';
            }
            try{
                $venda['preco'] = 'R$ '.number_format($venda->valor,2,',', '.');
            }
            catch(\Exception $e){
                $venda['preco'] = 'Preço Inválido';
            }
            try{
                $venda['categ'] =( $venda->categoria->name.' - '.$venda->categoria->description);
            }
            catch(\Exception $e){
                $venda['categ'] = 'Categoria Inválida';
            }
        }

        return DataTables::of($vendas)
            ->addColumn('action', function($data) use ($user){
                if($user->level >= Valores::ADMINISTRADOR){
                    $id = encrypt($data->id);
                    $button = '<button data-id="'.$id.'" class="btn btn-danger item del" data-toggle="tooltip" data-placement="top" title="Cancelar">Cancelar</i></button>';
                    return $button;
                }
            })->make(true);
    }
    function formatCnpjCpf($value){
        $cnpj_cpf = preg_replace("/\D/", '', $value);

        if (strlen($cnpj_cpf) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        }

        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }
    public function novavenda(Request $request) {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'cliente' => ['required'],
            'forma' => ['required'],
            'quant' => ['required']
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Verifique se os dados foram preenchidos corretamente');
        }
        try{
            $clienteId = decrypt($request->cliente);
            $cliente = Consumers::find($clienteId);
            if($cliente != null){
                $dados['consumer_id'] = $cliente->id;
                $categoria = $cliente->categoria;
                $dados['tipo'] = Valores::CADASTRADO;
            }
            else{
                throw new Exception('Cliente Null');
            }
        }
        catch(Exception $e){
            $dados['consumer_name'] = $request->cliente;
            $regex  = "/[!|\@#$%*()_+'".'"'."1234567890-=+§¬¢£³²¹`{}º:;°<>,.*-]/";
            if (preg_match($regex, $request->cliente)) {
                return redirect()->route('addvendas')->with('error', 'Nome Inválido, por favor não inserir números nem caracteres especiais!!');
            }
            $categoria = Categorias::where('local_id', $user->local_id)->where('name', 'D')->get()->last();
            $dados['tipo'] = Valores::NAOCADASTRADO;
        }
        try{
            if($request->forma == 1 and is_numeric($request->quant)){
                $valortotal = $request->quant*$categoria->valor;
            }
            elseif($request->forma == 2 and is_numeric($request->quant)){
                $valortotal = $request->quant;
            }
            else{
                return redirect()->route('addvendas')->with('error', 'Quantidade e/ou valor a pagar é inválida !!');
            }
        }
        catch(\Exception $e){
            return redirect()->route('addvendas')->with('error', 'Quantidade e/ou valor a pagar é inválida !!');
        }
        $dados['local_id'] = $user->local_id;
        $dados['categoria_id'] = ($request->tipo == 1)?$cliente->categoria->id: $categoria->id;
        $dados['forma'] = $request->forma;
        $dados['valor'] = $valortotal;
        if(!is_numeric($cliente->saldo)){
            return redirect()->route('addvendas')->with('error', 'Saldo do consumidor inválido');
        }
        try{
            $create = Vendas::all();
            if($create->count() == 0){
                $id = 1;
            }
            else{
                $id = $create->last()->id + 1;
            }
            if($dados['tipo'] == Valores::CADASTRADO){
                $saldo = round($cliente->saldo+$valortotal, 2);
                if(Vendas::create($dados) and $cliente->update(['saldo' => $saldo])){
                    $logs = array('func' => $user->id, 'local_id' => $user->local_id, 'action' => 'Realizou a venda '. $id);
                    UsersActions::create($logs);
                    return redirect()->route('addvendas')->with('success', 'Sucesso !!');
                }
                else{
                    return redirect()->back()->with('error', 'Erro ao fazer a venda !!');
                }
            }
            else{
                if(Vendas::create($dados)){
                    $logs = array('func' => $user->id, 'local_id' => $user->local_id, 'action' => 'Realizou a venda '. $id);
                    UsersActions::create($logs);
                    return redirect()->route('addvendas')->with('success', 'Sucesso !!');
                }
                else{
                    return redirect()->route('addvendas')->with('error', 'Erro ao fazer a venda !!');
                }
            }

        }
        catch(\Exception $e){
            return redirect()->back()->with('error', 'Erro ao fazer a venda !!');
        }
    }
    protected function sanitizeString($str) {
        $str = preg_replace('/[(),;:|!"#$%&=?><º-]/', '', $str);
        $str = preg_replace('/[0-9]/i', '', $str);
        return $str;
    }
}
