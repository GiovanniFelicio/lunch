<?php

Route::get('/', 'VendasController@show')->name('vendas');
Route::get('/add', 'VendasController@adicionar')->name('addvendas');
Route::get('/getdatavendas', 'VendasController@getdatavendas')->name('getdatavendas');
Route::post('/novavenda', 'VendasController@novavenda')->name('novavenda');