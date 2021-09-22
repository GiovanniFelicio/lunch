<?php

namespace App\Http\Controllers;

use App\Categorias;
use App\User;
use App\Valores;
use App\Locais;
use App\UsersActions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;
use Yajra\DataTables\DataTables;

class LocaisController extends Controller
{
    public function show(){
        $user = Auth::user();
        if ($user->level >= Valores::MASTER){
            return view('locais.locais');
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }
    }
    public function add(){
        $user = Auth::user();
        if($user->level < Valores::MASTER){
            return abort(403, 'Você não tem permissão para criar locais !!');
        }
        return view('locais.adicionar');
    }
    public function create(Request $request){
        $user = Auth::user();
        if($user->level >= Valores::MASTER){
            if(Locais::where('email', '=', $request->email)->count() >= 1){
                return redirect()->back()->with('error', 'Esse local Já existe');
            }
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:60'],
                'email' => ['required', 'string', 'max:60']
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Verifique se os dados foram preenchidos corretamente');
            }
            else{
                $dados = array('name' => $request->name, 'email' => $request->email);
                $dados1 = array('name' => 'D', 'description' => 'Não Cadastrado', 'local_id' => $user->local_id, 'valor' => 0);
                if (Locais::create($dados) and Categorias::create($dados1)){
                    $logs = array('func' => $user->id, 'local_id' => $user->local_id, 'action' => 'Criou o local: '. $request->name);
                    UsersActions::create($logs);
                    return redirect()->route('locais')->with('success', 'Local adicionado com Sucesso !!');
                }
                else{
                    return redirect()->route('locais')->with('error', 'Erro ao tentar adicionar este local !!');
                }
            }
        }
        else{
            return abort(403, 'Você não tem permissão');
        }
    }

    public function getdata(){
        $user = Auth::user();
        if($user->level >= Valores::MASTER){
            $locais = Locais::latest()->get();
            return DataTables::of($locais)
                ->addColumn('action', function($data){
                    $button = '<a href="'.route('viewlocal',encrypt($data->id)).'"><button type="submit" class="btn btn-primary item" data-toggle="tooltip" data-placement="top" title="View"><i class="fas fa-eye"></i></button></a>&nbsp;&nbsp;';
                    return $button;
                })->make(true);
        }
        else{
            return abort(403, 'Você não tem permissao suficente');
        }
    }
    public function view($id){
        $user = Auth::user();
        if ($user->level >= Valores::MASTER){
            $allUsers = array();
            try{
                $local = Locais::find(decrypt($id));
            }
            catch(\Exception $e){
                return redirect()->back()->with('error', 'Erro no sistema !!');
            }
        }
        else{
            abort(403, 'Você não é um Administrador');
        }

        return view('locais.view', compact('local', 'allUsers'));
    }
    public function getdataadms($idLoc){
        $user = Auth::user();
        if($user->level >= Valores::MASTER){
            $usuarios = User::where('local_id', decrypt($idLoc))->where('status','!=', 0)->get();
            $count = count($usuarios);
            for ($i = 0; $i < $count; $i++) {
                if ($usuarios[$i]->local_id == 0 or $usuarios[$i]->local_id == null) {
                    $usuarios[$i]->local = 'Sem Local';
                } else {
                    $usuarios[$i]->local = Locais::find($usuarios[$i]->local_id)->name;
                }
                $usuarios[$i]['idd'] = encrypt($usuarios[$i]->id);
            }
        }
        else{
            return abort(403, 'Você não tem permissão suficente');
        }
        return DataTables::of($usuarios)
            ->addColumn('action', function($data) use ($user){
                if(Auth::user()->level >= Valores::MASTER){
                    $id = encrypt($data->id);
                    if($user->level == Valores::MASTER){
                        $button = $this->selectLevel($id, $data->level, 3);
                    }
                    return $button;
                }
                else{
                    return '-';
                }
            })->make(true);
    }
    public function changerole(Request $request){
        $user = Auth::user();
        if($user->level >= Valores::MASTER){
            $validator = Validator::make($request->all(), [
                'usuario' => ['required', 'string'],
                'role' => ['required', 'string'],
            ]);
            if ($validator->fails()) {
                return 'Dados Inválidos !!';
            }
            else{
                try{
                    $usua = decrypt($request->usuario);
                    $level = decrypt($request->role);
                }
                catch(\Exception $e){
                    return 'Valores Inválidos';
                }
                try{
                    $usuario = User::find($usua); 
                }
                catch(\Exception $f){
                    return 'Esse Funcionário não existe';
                }
                switch($level){
                     case Valores::MODERADOR || Valores::ADMINISTRADOR:
                        if($user->level >= Valores::MASTER){
                            $usuario->level = $level;
                        }
                        else{
                            return 'Você não tem permissão para esse nível de autorização';
                        }
                        break;
                     case Valores::MASTER:
                        if($user->level == Valores::MASTER){
                            $usuario->level = $level;
                        }
                        else{
                            return 'Você não tem permissão para esse nível de autorização';
                        }
                        break;
                    default:
                        return 'Nível de autorização inválido';
                        break; 
                }
                
                if($usuario->update()){
                    return 1;
                }
                else{
                    return 0;
                }
            }
        }
        else{
            return abort(403, "Você não tem permissão suficiente");
        }
    }
    protected function selectLevel($id, $level, $quantia){
        $button = '<select data-id="'.$id.'" class="selectStaffs form-control">';
        $roles = [1 => 'Moderador', 2 => 'Administrador', 3 => 'Master'];
        for($i = 1;$i <= $quantia;$i++){
            if($level == $i){
                $button .= '<option selected value="'.encrypt($i).'">'.$roles[$i].'</option>';
            }
            else{
                $button .= '<option value="'.encrypt($i).'">'.$roles[$i].'</option>';
            }
        }
        $selectClose = $button.'</select>';
        return $selectClose;
    }
}
