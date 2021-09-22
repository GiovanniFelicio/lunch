<?php

namespace App\Http\Controllers;

use App\Cad;
use App\Locais;
use App\User;
use App\UsersActions;
use App\Valores;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function mostra(Request $request){
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            $funcionarios = User::all();
            $count = count($funcionarios);
            for ($i = 0; $i < $count; $i++) {
                if ($funcionarios[$i]->local_id == 0 or $funcionarios[$i]->local_id == null) {
                    $funcionarios[$i]->local = 'Sem Sec/Aut';
                } else {
                    $funcionarios[$i]->local = Locais::find($funcionarios[$i]->local_id)->name;
                }
            }
            $locais = Locais::all();
            return view('admin.users',compact('user','funcionarios','locais'));
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }
    }
    public function getdatausers(){
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            $usuarios = User::where('status', 1)->get();
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
                if($user->level >= Valores::ADMINISTRADOR){
                    $id = encrypt($data->id);
                    $button = '<button data-id="'.$id.'" class="btn btn-danger item del" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash-alt"></i></button>';
                    return $button;
                }
            })->make(true);

    }
    public function criaUser(){
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            $locais = Locais::where('status', 1)->get();
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }
        return view('admin.criauser', compact('user', 'locais'));
    }
    public function create(Request $request){
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            try{
                $local = decrypt($request->local);
            }
            catch(\Exception $e){
                return redirect()->route('users')>with('error', 'Secretaria inválida');
            }

            if(Locais::where('id', $local)->get()->count() == 1){

                $validator = Validator::make($request->all(), [
                    'name' => ['required', 'string', 'max:191'],
                    'email' => ['required', 'email', 'max:191'],
                    'matricula' => ['required', 'string', 'max:100']
                ]);
                if ($validator->fails()) {
                    return redirect()->route('users')->with('error', 'Verifique se os dados foram preenchidos corretamente');
                }
                else{
                    $request['password'] = bcrypt(123456789);
                    $usuario= User::where('email', $request['email'])->get();
                    if($usuario->count() == 1){
                        $usua = $usuario->last();
                        if ($usua->status == 1){
                            return redirect()->route('users')->with('error', 'E-mail ja cadastrado');
                        }
                        else{
                            $usua->status = 1;
                            if($usua->update()){
                                return redirect()->route('users')->with('success','Funcionário adicionado com sucesso');
                            }
                            else{
                                return redirect()->route('users')->with('error','Error ao adicionar o Funcionário, caso persista comunique o setor de informática da FUNDETEC.');
                            }
                        }
                    }
                    elseif(User::where('matricula', $request->matricula)->get()->count() == 1){
                        return redirect()->route('users')->with('error', 'Matricula já cadastrada');
                    }
                    else {
                        $dados = array('local_id' => $local, 'name' => $request->name,'email' => $request->email, 'password' => $request->password, 'level' => $request->level, 'matricula' => $request->matricula);
                        $create = User::create($dados);
                        if ($create == true){
                            $logs = array('func' => $user->id, 'local_id' => $user->local_id, 'action' => 'Criou o funcionário '.$create->name);
                            UsersActions::create($logs);
                            return redirect()->route('users')->with('success','Funcionario adicionado com sucesso');
                        }
                        else{
                            return redirect()->route('users')->with('error','Error ao adicionar o Funcionario, caso persista comunique o setor de informatica da FUNDETEC.');
                        }
                    }
                }
            }
            else{
                return redirect()->route('users')->with('error', 'Secretaria/Autarquia Invalida');
            }
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }
    }
    protected function validateAndCreateFunc($request){

        $validator = Validator::make($request, [
            'name' => ['required', 'string', 'max:191'],
            'setor_id' => ['required', 'string', 'max:4'],
            'email' => ['required', 'email', 'max:191'],
            'matricula' => ['required', 'string', 'max:100']
        ]);

        if ($validator->fails()) {
            return 'error';
        }
        else{
            return 'success';
        }
    }
    public function updateUser(Request $request){
        $user = Auth::user();
        $idUser = decrypt($request->reference);
        $dados = array();
        if($user->level >= Valores::ADMINISTRADOR){
            $userUp = User::find($idUser);
            if ($userUp == null){
                return redirect()->route('users')->with('error', 'Erro !! Usuário inválido');
            }
            if($request->nameFunc != null){
                $dados['name'] = $request->nameFunc;
            }
            if($request->level != null){
                if($request->level == 1 or $request->level == 2 or $request->level == 3 or $request->level == 4 or $request->level == 5){
                    $dados['level'] = $request->level;
                }
            }
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

            if ($request->matricula != null){
                $dados['matricula'] = $request->matricula;
            }
            $dados['token_access'] = null;
            if ($userUp->update($dados)){
                $logs = array('func' => $user->id, 'local_id' => $user->local_id, 'action' => 'Atualizou o Funcionário '.$userUp->name);
                UsersActions::create($logs);
                return 1;
            }
            else{
                return 2;
            }
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }

    }
    public function searchuser($idd)
    {
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            $func = User::find(decrypt($idd));
            $dados = array('func' => $func->name,
                            'local' => $func->local->name,
                            'email' => $func->email,
                            'matricula' => $func->matricula,
                            'level' => $func->level);
            return response()->json($dados);
        }
        else{
            return abort(403, 'Você não tem permissao suficente');
        }
    }

    public function profile(Request $request){

        $user = $request->user();
        $name = $user->name;
        $email = $user->email;
        return view('user.profile', compact('name', 'email'));

    }
    public function editUser(Request $request){
        $user = $request->user();
        $name = $user->name;
        $email = $user->email;
        $level = $user->level;
        return view('auth.updateAccount', compact('name', 'email', 'level'));
    }
    public function delete($id){
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR) {
            $usuario = User::find(decrypt($id));
            $usuario->status = 0;
            if ($usuario->update())
            {
                $logs = array('func' => $user->id, 'local_id' => $user->local_id, 'action' => 'Desativou o funcionário '.$usuario->name);
                UsersActions::create($logs);
                return redirect()->route('users')->with('error','Deletado com Sucesso !!');
            }
            else{
                return redirect()->route('users')->with('error','Erro ao Deletar');
            }
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }
    }

    public function roda(){
        $cad = Cad::all();
        $dados = array();
        foreach($cad as $key => $c){
            if($this->validaCPF($c->cpf) == false){
                $c->delete();
            }
        }
        return 1;
    }
    protected function validaCPF($cpf) {

        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
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
}
