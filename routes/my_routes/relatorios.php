<?php

Route::get('/', 'RelatoriosController@show')->name('relatorios');
Route::post('/generate', 'RelatoriosController@generate')->name('generate');