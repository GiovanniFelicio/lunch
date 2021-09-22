<?php

Route::get('/getdata/{id}', 'BairrosController@getdata')->name('getdataBairros');
Route::post('/auth', 'BairrosController@authBairro')->name('authBairro');
Route::post('/disallowance', 'BairrosController@disallowance')->name('disallowanceBairro');

