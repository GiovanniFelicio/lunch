<?php

namespace App\Http\Controllers;

use App\Bairros;
use App\BairrosAuth;
use App\Categorias;
use App\Valores;
use App\Locais;
use App\Consumers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\DataTables;

class BairrosController extends Controller
{
    public function getdata($idLocalCrypt){
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            $bairros = Bairros::all();
            $dados = array();
            foreach($bairros as $key => $bairro){
                $dados[$key]['reference'] = encrypt($bairro->id);
                $dados[$key]['id'] = $bairro->id;
                $dados[$key]['nome'] = $bairro->name;
            }
        }
        else{
            return abort(403, 'Você não tem permissão suficente');
        }
        return DataTables::of($dados)
            ->addColumn('action', function($data) use ($user, $idLocalCrypt){
                if($user->level >= Valores::ADMINISTRADOR){
                    if(BairrosAuth::where('bairro_id', $data['id'])->where('local_id', decrypt($idLocalCrypt))->where('status', 1)->get()->count() == 1){
                        $button = '<label class="switch"><input data-id="'.$data['reference'].'" class="toggleAuth" type="checkbox" checked><span class="slider round"></span></label>';
                    }
                    else{
                        $button = '<label class="switch"><input data-id="'.$data['reference'].'" class="toggleAuth" type="checkbox"><span class="slider round"></span></label>';
                    }
                    return $button;
                }
            })->make(true);
    }

    public function authBairro(Request $request){
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            $validator = Validator::make($request->all(), [
                'bairro' => ['required', 'string'],
                'local' => ['required', 'string'],
            ]);
            if ($validator->fails()) {
                return 'Dados Inválidos !!';
            }
            else{
                try{
                    $bairroId = decrypt($request->bairro);
                    $localId = decrypt($request->local);
                }
                catch(\Exception $e){
                    return 'Valores Inválidos';
                }
                try{
                   $local = Locais::find($localId); 
                }
                catch(\Exception $f){
                    return 'Esse Veículo não existe';
                }
                if(BairrosAuth::where('local_id', $localId)->where('bairro_id', $bairroId)->where('status', 1)->get()->count() == 1){
                    return 'Este Bairro já foi Designado';
                }
                elseif(BairrosAuth::where('local_id', $localId)->where('bairro_id', $bairroId)->where('status', 0)->get()->count() == 1){
                    $up = BairrosAuth::where('local_id', $localId)->where('bairro_id', $bairroId)->where('status', 0)->get()->last();
                    $up->status = 1;
                    if($up->update()){
                        return 1;
                    }
                    else{
                        return 0;
                    }
                }
                else{
                    $dados = array('bairro_id' => $bairroId, 'local_id' => $localId);
                    if(BairrosAuth::create($dados)){
                        return 1;
                    }
                    else{
                        return 0;
                    }
                }
            }
        }
        else{
            return abort(403, "Você não tem permissão suficiente");
        }
    }
    public function disallowance(Request $request){
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            $validator = Validator::make($request->all(), [
                'bairro' => ['required', 'string'],
                'local' => ['required', 'string'],
            ]);
            if ($validator->fails()) {
                return 'Dados Inválidos !!';
            }
            else{
                try{
                    $bairroId = decrypt($request->bairro);
                    $localId = decrypt($request->local);
                }
                catch(\Exception $e){
                    return 'Valores Inválidos';
                }
                try{
                   $local = Locais::find($localId); 
                }
                catch(\Exception $f){
                    return 'Esse Veículo não existe';
                }
                if(BairrosAuth::where('local_id', $localId)->where('bairro_id', $bairroId)->where('status', 0)->get()->count() == 1){
                    return 'Este Bairro já foi Designado';
                }
                elseif(BairrosAuth::where('local_id', $localId)->where('bairro_id', $bairroId)->where('status', 1)->get()->count() == 1){
                    $up = BairrosAuth::where('local_id', $localId)->where('bairro_id', $bairroId)->where('status', 1)->get()->last();
                    $up->status = 0;
                    if($up->update()){
                        return 1;
                    }
                    else{
                        return 0;
                    }
                }
                else{
                    $dados = array('bairro_id' => $bairroId, 'local_id' => $localId);
                    if(BairrosAuth::create($dados)){
                        return 1;
                    }
                    else{
                        return 0;
                    }
                }
            }
        }
        else{
            return abort(403, "Você não tem permissão suficiente");
        }
    } 
}
