<?php

Route::get('/', 'LocaisController@show')->name('locais');
Route::get('/getdatalocais', 'LocaisController@getdata')->name('getdatalocais');
Route::get('/getdataadms/{id}', 'LocaisController@getdataadms')->name('getdataAdms');
Route::get('/add', 'LocaisController@add')->name('adicionarlocais');
Route::post('/create', 'LocaisController@create')->name('createlocal');
Route::get('/view/{id}', 'LocaisController@view')->name('viewlocal');
Route::post('/changerole', 'LocaisController@changerole')->name('changerole');