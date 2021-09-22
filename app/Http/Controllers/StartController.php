<?php

namespace App\Http\Controllers;

use App\UltimosAcessos;
use App\Vendas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\DataTables;


class StartController extends Controller
{
    public function barcode()
    {
        return view('user.barcodegenerator');
    }

    public function start(Request $request)
    {
        $user = $request->user();
        $name = $user->name;
        
        return view('user.start', compact('name'));
    }

}
