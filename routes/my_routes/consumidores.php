<?php

Route::get('/', 'ConsumerController@show')->name('consumidores');
Route::get('/getdataconsumers', 'ConsumerController@getdataconsumers')->name('getdataconsumers');
Route::get('/add', 'ConsumerController@adicionar')->name('addconsumidor');
Route::post('/create', 'ConsumerController@create')->name('createConsumer');
Route::post('/update', 'ConsumerController@update')->name('updateCons');
Route::get('/delete/{id}', 'ConsumerController@delete');
Route::get('/search/{id}', 'ConsumerController@search');
Route::get('/searchcep/{id}', 'ConsumerController@cep');
Route::get('/searchcateg/{id}', 'ConsumerController@searchcateg');