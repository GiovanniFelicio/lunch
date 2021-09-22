<?php

Route::get('/', 'CategoriasController@show')->name('categorias');
Route::get('/add', 'CategoriasController@adicionar')->name('addcategorias');
Route::get('/getdatacateg', 'CategoriasController@getdatacateg')->name('getdatacateg');
Route::post('/create', 'CategoriasController@novacategoria')->name('novacategoria');
Route::get('/delete/{id}', 'CategoriasController@delete');
Route::get('/search/{id}/{tipo}', 'CategoriasController@search');
Route::get('/searchnotcadas/{name}', 'CategoriasController@searchnotcadas');
Route::post('/update', 'CategoriasController@update')->name('updatecateg');