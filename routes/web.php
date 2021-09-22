<?php

use App\Vendas;
use Illuminate\Http\Request;

use yajra\Datatables\Datatables;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/bar','StartController@barcode');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Auth::routes(['register' => false]);

Route::get('/', function () {
    try{
        Auth::logout();
    }
    catch (Exception $exception){
        return redirect('/login');
    }
    return redirect('/login');
});

Route::group(['prefix' => '/inicio', 'middleware' => ['auth', 'web', 'auth.unique.user']], function () {
    Route::get('/','StartController@start')->name("start");
});
