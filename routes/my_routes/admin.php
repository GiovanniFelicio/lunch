<?php

Route::get('/users', 'UserController@mostra')->name('users');
Route::get('/delete/{id}', 'UserController@delete');
Route::get('/getdatausers', 'UserController@getdatausers')->name('getdatausers');
Route::get('/register', 'UserController@criauser')->name('register');
Route::post('/saveUser', 'UserController@create')->name('saveUser');
Route::get('/editarConta', 'UserController@editUser')->name('editConta');
Route::post('/update', 'UserController@update')->name('updateAccount');
Route::post('/updateuser', 'UserController@updateUser')->name('updateUser');
Route::get('/searchuser/{id}', 'UserController@searchuser');
