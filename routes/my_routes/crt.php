<?php

Route::get('/', 'CrtController@show')->name('crt');
Route::post('/', 'CrtController@crtvalidation')->name('crtvalidation');