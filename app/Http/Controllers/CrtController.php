<?php

namespace App\Http\Controllers;

use App\Categorias;
use App\Consumers;
use App\Crt;
use App\Valores;
use Auth;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Validator;


class CrtController extends Controller
{
    public function show()
    {
        return view('crt.crt');
    }
    public function crtvalidation(Request $request)
    {
        date_default_timezone_set('America/Bahia');
        $date = new DateTime();
        $data = $date->format('Y-m-d');
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            $validator = Validator::make($request->all(), [
                'name' => ['required'],
            ]);
            if ($validator->fails()) {
                return [Valores::ERROVALIDACAO];
            }
            try{
                $consumer = Consumers::where('cpf', $request->name)->where('status',1)->get()->last();
                if($consumer == null){
                    throw new Exception('Consumidor Null');
                }
            }
            catch(Exception $e){
                return [Valores::CONSUNOTFOUND];
            }
            try{
                $categoria = Categorias::find($consumer->categoria_id);
            }
            catch(Exception $e){
                return [Valores::CATEGNOTFOUND];
            }
            $crt = Crt::where('consumer_id', $consumer->id)->get()->last();
            if($crt != null){
                $crtData = Carbon::parse($crt->created_at)->format('Y-m-d');
                if($crtData == $data){
                    return [Valores::MAXLIMIT];
                }
            }
            
            $saldoF = ($consumer->saldo - $categoria->valor);
            if($saldoF < 0){
                return [Valores::INSUFICIENTE];
            }
            $dados = array('consumer_id' => $consumer->id, 
                            'local_id' => $user->local_id, 
                            'categoria_id' => $consumer->categoria_id);
            if($consumer->update(['saldo' => $saldoF]) and Crt::create($dados)){
                return [Valores::AUTORIZADO, $saldoF];
            }
            else{
                return [Valores::ERRORINTERNAL];
            }
        }
        else{
            return [Valores::ERRRORPERMISSAO];
        }
    }


}
