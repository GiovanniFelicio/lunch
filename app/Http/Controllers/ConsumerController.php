<?php

namespace App\Http\Controllers;

use App\Bairros;
use App\BairrosAuth;
use App\Categorias;
use App\Valores;
use App\UsersActions;
use App\Locais;
use App\Consumers;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Intervention\Image\Facades\Image;

class ConsumerController extends Controller
{
    public function show(){
        $user = Auth::user();
        $locais = Locais::where('status', 1)->get(['id', 'name']);
        $categorias = Categorias::where('status',1)->get(['id', 'name', 'description']);
        $dados1 = array();
        foreach($categorias as $key => $categoria){
            $dados1[$key]['refcateg'] = encrypt($categoria->id);
            $dados1[$key]['nomecateg'] = $categoria->name.' - '.$categoria->description;
        }
        return view('consumers.consumers', compact('user', 'dados1'));
    }
    public function adicionar(){
        $user = Auth::user();
        if($user->level < Valores::ADMINISTRADOR){
            return abort(403, 'Você não é administrador');
        }
        $bairrosAuth = BairrosAuth::where('local_id', $user->local_id)->where('status', 1)->get('bairro_id')->toArray();
        $bairros = Bairros::whereIn('id', $bairrosAuth)->get();
        $categorias = Categorias::where('status',1)->orderBy('name', 'ASC')->get(['id', 'name', 'description']);
        $dados1 = array();
        foreach($categorias as $key => $categoria){
            $dados1[$key]['refcateg'] = encrypt($categoria->id);
            $dados1[$key]['nomecateg'] = $categoria->name.' - '.$categoria->description;
        }
        return view('consumers.adicionar', compact( 'user', 'dados1', 'bairros'));
    }
    public function getdataconsumers(){
        $user = Auth::user();
        if($user->level >= Valores::MODERADOR){
            $consumers = Consumers::all();
            $dados = array();
            foreach($consumers as $key => $consumer){
                $dados[$key]['idd'] = encrypt($consumer->id);
                $dados[$key]['nome'] = $consumer->name;
                $dados[$key]['cpf'] = $consumer->cpf;
                $dados[$key]['rg'] = $consumer->rg;
                $dados[$key]['nis'] = $consumer->nis;
                $dados[$key]['categoria'] = ($consumer->categoria->name.' - '.$consumer->categoria->description) ?? 'Categoria Inválida';
            }
        }
        else{
            return abort(403, 'Você não tem permissão suficente');
        }
        return DataTables::of($dados)
            ->addColumn('action', function($data) use ($user){
                if($user->level >= Valores::ADMINISTRADOR){
                    $id = $data['idd'];
                    $button = '<button data-id="'.$id.'" class="btn btn-info item generate" data-toggle="tooltip" data-placement="top" title="Generate">Gerar Cartão</button>&nbsp;';
                    $button .= '<button data-id="'.$id.'" class="btn btn-danger item del" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash-alt"></i></button>';
                    return $button;
                }
            })->make(true);
    }
    public function create(Request $request){
        $user = Auth::user();
        date_default_timezone_set('America/Bahia');
        $date = new DateTime();
        $prazo = $date->format('Y-m-d');
        if($user->level >= Valores::ADMINISTRADOR){
            $this->validate($request,[
                'name' => 'required',
                'photo' => 'required',
                'cpf' => 'required',
                'nasc' => 'required|date_format:Y-m-d',
                'sexo' => 'required',
                'rg' => 'required',
                'cep' => 'required',
                'numero' => 'required|integer',
                'renda' => 'required',
                'dependentes' => 'required'
            ],[
                'name.required' => 'Campo nome é obrigatório',
                'photo.required' => 'Campo de foto é obrigatório',
                'cpf.required' => 'Campo de CPF é obrigatório',
                'nasc.required' => 'Campo de Nascimento é obrigatório',
                'nasc.date_format' => 'Formato de data inválido',
                'sexo.required' =>'CheckBox Inválido para Sexo',
                'rg.required' => 'Campo de RG é obrigatório',
                'cep.required' => 'Campo de CEP é obrigatório',
                'numero.integer' => 'Número Inválido',
                'renda.required' => 'Campo de Renda é obrigatório',
                'dependentes.required' => 'Marque o CheckBox de dependentes'
            ]);
            if($request->dependentes == 1){
                $this->validate($request,[
                    'numeroDepen' => 'required|numeric'
                ],[
                    'numeroDepen.required' => 'Selecione a quantidade válida de dependentes',
                    'numeroDepen.numeric' => 'Campo de número de dependentes deve ser numérico',
                ]);
                $final = ($request->renda/($request->numeroDepen+1));
            }
            else{
                $final = $request->renda;
            }
            if(!in_array($request->sexo, ['m','f'])){
                return redirect()->back()->with('error', 'Sexo Inválido');
            }
            $categoria = $this->searchCat($final);
            $number = $request->numero;
            if(!$this->validaCPF($request->cpf)){
                return redirect()->back()->with('error', 'Este CPF não é válido');
            }
            $consumer = Consumers::orWhere('cpf', $request->cpf)->orWhere('email', $request->email)->get()->last();
            if($consumer != null){
                if($consumer->update()){
                    return redirect()->route('consumidores')->with('success', 'Consumidor adicionado com Sucesso !!');
                }
                else{
                    return redirect()->route('consumidores')->with('error', 'Erro ao tentar adicionar este consumidor !!');
                }
            }
            $cep = $this->seo_friendly_url($request->cep);

            try{
                $validacep = file_get_contents('https://viacep.com.br/ws/'.$cep.'/json/');
                $valida = json_decode($validacep);
                $erro = $valida->erro;
                return redirect()->route('addconsumidor')->with('error', 'CEP Inválido !!');
            }
            catch(\Exception $e){
                if(Bairros::where('name', $valida->bairro)->get()->count() == 0){
                    return redirect()->route('addconsumidor')->with('error', 'Dirija-se ao local correto de seu bairro !!');
                }
                $name = $request->cpf.".jpeg";
                //$dateF = date('Y-m-d',strtotime('+45 days',strtotime($prazo)));
                $dados = array('name' => $request->name,
                            'cpf' => $request->cpf ?? null,
                            'rg' => $request->rg ?? null,
                            'sexo' => $request->sexo,
                            'email' => strtolower($request->email) ?? null,
                            'nascimento' => $request->nasc ?? null,
                            'nis' => $request->nis,
                            'categoria_id' => $categoria,
                            'cep' => $cep,
                            'complement' => $request->complemento ?? ' ',
                            'number' => $number,
                            'dependentes' => $request->dependentes,
                            'numdepen' => $request->numeroDepen,
                            'renda' => $request->renda);
                $create = Consumers::create($dados);
                if ($create and Image::make(file_get_contents($request->photo))->save(storage_path('app/public/consumers/'.$name))){
                    $logs = array('func' => $user->id, 'local_id' => $user->local_id, 'action' => 'Criou o consumidor: '. $request->cpf);
                    UsersActions::create($logs);
                    return redirect()->route('consumidores')->with('success', 'Consumidor N° '.$create->id.' adicionado com Sucesso !!');
                }
                else{
                    return redirect()->route('consumidores')->with('error', 'Erro ao tentar adicionar este consumidor !!');
                }
            }
        }
        else{
            return abort(403, 'Você não tem permissão');
        }
    }
    function seo_friendly_url($string){
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '', $string);
        return strtolower(trim($string, '-'));
    }
    protected function generatePassword($value1){ 
        do{
            $rand = mt_rand(0, 99999);
            $pass = $value1.$rand;
            $senhaJaExiste = Consumers::where('password', $pass)->first();
        }while($senhaJaExiste !== null);

        return $pass;
    }
    protected function validaCPF($cpf) {
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
        if (strlen($cpf) != 11) {
            return false;
        }
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }
        return true;
    }
    public function delete($idd){
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            try{
                $id = decrypt($idd);
                $consumer = Consumers::find($id);
                $consumer->update(['status' => 0]);
                return redirect()->route('consumidores')->with('success', 'Sucesso !!');
            }
            catch(\Exception $e){
                return redirect()->route('consumidores')->with('error', 'Error');
            }
        }
        else{
            return abort(403, 'Você não tem permissão !!');
        }
    }
    public function search($id){
        $user = Auth::user();
        if($user->level >= Valores::MODERADOR){
            $consu = Consumers::find(decrypt($id));
            $dados = array('idd' => encrypt($consu->id),
                            'nome' => $consu->name,
                            'cpf' => $consu->cpf,
                            'rg' => $consu->rg,
                            'email' => $consu->email,
                            'nasc' => $consu->nascimento,
                            'categoria' => ($consu->categoria->name.' - '.$consu->categoria->description));
            return response()->json($dados);
        }
        else{
            return abort(403, 'Error');
        }
    }
    public function update(Request $request){
        $user = Auth::user();
        $idConsumer = decrypt($request->reference);
        if($user->level >= Valores::ADMINISTRADOR){
            $consumerUp = Consumers::find($idConsumer);
            if ($consumerUp == null){
                return 'Consumidor Inválido';
            }
            if($request->cpf != null){
                if(!$this->validaCPF($request->cpf)){
                    return 'CPF Inválido !!';
                }
            }
            $validator = Validator::make($request->all(), [
                'name' => ['required'],
                'cpf' => ['required'],
                'rg' => ['required'],
                'nasc' => ['required']
            ]);
            if ($validator->fails()) {
                return 'É obrigatório preencher: Nome, CPF, RG, Data de nascimento se o tipo fo igual a: Baixa/Média Renda ou Funcionário';
            }
            $dados = array( 'name'       => $request->name,
                            'cpf'        => $request->cpf,
                            'rg'         => $request->rg,
                            'email'      => $request->email,
                            'nascimento' => $request->nasc,
                            'categoria_id' => $request->categoria);
            if ($request->local != null) {
                try {
                    $local = decrypt($request->local);
                    if (Locais::find($local) != null) {
                        $dados['local_id'] = $local;
                    }
                } catch (\Exception $exception) {
                    //
                }
            }
            if ($consumerUp->update($dados)){
                $logs = array('func' => $user->id, 'local_id' => $user->local_id, 'action' => 'Atualizou o Consumidor '.$consumerUp->name);
                UsersActions::create($logs);
                return 1;
            }
            else{
                return 'Erro no Sistema ao autualizar !!';
            }
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }
    }
    public function cep($cep){
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            $cep1 = $this->seo_friendly_url($cep);
            $validacep = file_get_contents('https://viacep.com.br/ws/'.$cep1.'/json/');
            $valida = json_decode($validacep);
            try{
                if($valida->erro == true){
                    return 'error';
                }
            }
            catch(\Exception $e){
                $bairro = Bairros::where('name', $valida->bairro)->get()->last();
                if($bairro == null){
                    return 'error';
                }
                $bairroauth = BairrosAuth::where('bairro_id', $bairro->id)->where('local_id', $user->local_id)->get()->count();
                if($bairroauth == 1){
                    $dados = array('rua' => $valida->logradouro, 'bairro' => $valida->bairro, 'localidade' => $valida->localidade, 'uf' => $valida->uf);
                    return $dados;
                }
                else{
                    return 'error';
                }
            }
        }
        else{
            return 'Sem Permissão';
        }
    }
    public function searchcateg($value){
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            if(is_numeric($value)){
                $categorias = Categorias::all();
                foreach($categorias as $categoria){
                    switch($categoria->forma){
                        case 1:
                            if($categoria->inicial <= $value and $categoria->final >= $value){
                                $dados = $categoria->name.' - '.$categoria->description;
                                return $dados;
                            }
                        break;
                        default:
                            if($categoria->acima <= $value){
                                $dados = $categoria->name.' - '.$categoria->description;
                                return $dados;
                            }
                        break;
                    } 
                }
            }
            else{
                return 'error';
            }
           
        }
        else{
            return 'Sem Permissão';
        }
    }
    protected function searchCat($value){
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            if(is_numeric($value)){
                $categorias = Categorias::all();
                foreach($categorias as $categoria){
                    switch($categoria->forma){
                        case 1:
                            if($categoria->inicial <= $value and $categoria->final >= $value){
                                return $categoria->id;
                            }
                        break;
                        default:
                            if($categoria->acima <= $value){
                                return $categoria->id;
                            }
                        break;
                    } 
                }
            }
            else{
                return 'error';
            }
           
        }
        else{
            return 'Sem Permissão';
        }
    }
}
