<?php

namespace App\Http\Controllers;

use App\Categorias;
use App\UsersActions;
use App\Locais;
use App\Consumers;
use App\Valores;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CategoriasController extends Controller
{
    public function show(){
        return view('categorias.categorias');
    }
    public function adicionar(){
        $user = Auth::user();
        if($user->level < Valores::ADMINISTRADOR){
            return abort(403, 'Você não é administrador');
        }
        return view('categorias.adicionar', compact('user'));
    }
    public function getdatacateg(){
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            $categorias = Categorias::where('status',1)->get();
        }
        $dados = array();
        foreach($categorias as $key => $categoria){
            $dados[$key]['descricao'] = $categoria->description;
            $dados[$key]['nome'] = $categoria->name;
            try{
                $dados[$key]['preco'] = 'R$ '.$categoria->valor;
            }
            catch(\Exception $e){
                $dados[$key]['preco'] = 'Preço Inválido';
            }
            try{
                switch($categoria->id){
                    case $categoria->id < 10:
                        $dados[$key]['codigo'] = '000000'.$categoria->id;
                    break;
                    case $categoria->id < 100:
                        $dados[$key]['codigo'] = '00000'.$categoria->id;
                    break;
                    case $categoria->id < 1000:
                        $dados[$key]['codigo'] = '0000'.$categoria->id;
                    break;
                    case $categoria->id < 100000:
                        $dados[$key]['codigo'] = '000'.$categoria->id;
                    break;
                    case $categoria->id < 1000000:
                        $dados[$key]['codigo'] = '00'.$categoria->id;
                    break;
                }
            }
            catch(\Exception $e){
                $dados[$key]['codigo'] = 'Inválido';
            }
            $dados[$key]['reference'] = encrypt($categoria->id);
        }

        return DataTables::of($dados)->make(true);
    }
    public function novacategoria(Request $request) {
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            setlocale(LC_MONETARY, 'pt_BR');
            $this->validate($request, [
                'namecateg' => 'required',
                'descricao' => 'required',
                'preco' => 'required|numeric',
                'forma' => 'required'
            ],[
                'namecateg.required' => ' O Campo de nome da categoria é obrigatório',
                'descricao.required' => ' O Campo da descrição é obrigatório',
                'preco.required' => ' O Campo do preço é obrigatório',
                'preco.numeric' => ' O preço deve ser um numérico',
                'forma.required' => ' O CheckBox é obrigatório',
            ]);
            $preco = number_format($request->preco,2,'.','');
            if($request->forma == 1){
                $this->validate($request,[
                   'inicial' => 'required|numeric',
                   'final' => 'required|numeric'
                ],[
                    'inicial.required' => ' O Campo de valor inicial é obrigatório',
                    'inicial.numeric' => ' O Campo da valor inicial deve ser numérico',
                    'final.required' => ' O Campo de valor final é obrigatório',
                    'final.numeric' => ' O Campo de valor final deve ser um numérico',
                ]);
                $inicial = number_format($request->inicial,2,'.','');
                $final = number_format($request->final,2,'.','');
                
            }
            elseif($request->forma == 2){
                $this->validate($request, [
                    'acima' => 'required|numeric'
                ],[
                    'acima.required' => ' O Campo de valor acima é obrigatório',
                    'acima.numeric' => ' O Campo da valor acima deve ser numérico'
                ],);
                $acima = number_format($request->acima,2,'.','');
            }
            else{
                return redirect()->back()->with('error', 'CheckBox Inválido');
            }
        }
        $categoria = Categorias::where('name', $request->namecateg)->get()->last();
        if($categoria != null){
            if($categoria->update(['name' => $request->namecateg, 'valor' => $preco, 'status' => 1])){
                $logs = array('func' => $user->id, 'local_id' => $user->local_id, 'action' => 'Criou a Categoria: '. $categoria->id);
                UsersActions::create($logs);
                return redirect()->route('categorias')->with('success', 'Sucesso !!');
            }
            else{
                return redirect()->route('categorias')->with('error', 'Error ao criar categoria !!');
            }
        }
        $create = Categorias::all();
        if($create->count() == 0){
            $id = 1;
        }
        else{
            $id = $create->last()->id + 1;
        }
        $dados = array('name' => $request->namecateg, 
                        'description' => $request->descricao, 
                        'valor' => $preco,
                        'forma' => $request->forma,
                        'inicial' => $inicial ?? null, 
                        'final' => $final ?? null, 
                        'acima' => $acima ?? null);
        if(Categorias::create($dados)){
            $logs = array('func' => $user->id, 'local_id' => $user->local_id, 'action' => 'Criou a Categoria: '. $id);
            UsersActions::create($logs);
            return redirect()->route('addcategorias')->with('success', 'Sucesso !!');
        }
        else{
            return redirect()->route('addcategorias')->with('error', 'Não foi possível adicionar essa Categoria');
        }
    }
    public function update(Request $request){
        $user = Auth::user();
        if($user->level >= Valores::ADMINISTRADOR){
            try{
                $categoria = Categorias::find(decrypt($request->reference));
            }
            catch(Exception $e){
                return 404;
            }
            $dados = array('description' => $request->descricao, 'valor' => $request->preco ?? 0);
            if($categoria->update($dados)){
                return 200;
            }
            else{
                return 500;
            }
        }
        else{
            return 403;
        }
    }
    public function delete($idd){
        $user = Auth::user();
        if($user->level >= Valores::MASTER){
            try{
                $id = decrypt($idd);
                $categoria = Categorias::where('id',$id)->where('name', '!=', 'D')->get()->last();
                if($categoria != null and $categoria->update(['status' => 0])){
                    return redirect()->route('categorias')->with('success', 'Sucesso !!');
                }
                else{
                    return redirect()->route('categorias')->with('error', 'Error');
                }
            }
            catch(\Exception $e){
                return redirect()->route('categorias')->with('error', 'Error');
            }
        }
        else{
            return abort(403, 'Você não tem permissão !!');
        }
    }
    public function search($idd, $tipo){
        $user = Auth::user();
        try{
            if($user->level >= Valores::MODERADOR and $tipo == 1){
                try{
                    $id = decrypt($idd);
                    $categ = Consumers::find($id)->categoria->valor;
                    return response()->json($categ);
                }
                catch(Exception $e){
                    $categ = Categorias::where('name', 'D')->get()->last()->valor;
                    return response()->json($categ);
                }
            }
            elseif($user->level >= Valores::MODERADOR and $tipo == 2){
                try{
                    $id = decrypt($idd);
                    $categ = Categorias::find($id);
                    $categoria = array('nome' => $categ->name, 'descricao' => $categ->description, 'money' => $categ->valor);
                    return response()->json($categoria);
                }
                catch(Exception $e){
                    return 404;
                }
            }
            else{
                return abort(403, 'Error');
            }
        }
        catch(Exception $e){
            return abort(403, 'Error');
        }

    }
    public function searchNotCadas($namecateg){
        $user = Auth::user();
        if($user->level >= Valores::MODERADOR){
            $categ = Categorias::where('name',decrypt($namecateg))->get()->last()->valor;
            return response()->json($categ);
        }
        else{
            return abort(403, 'Error');
        }
    }
}
