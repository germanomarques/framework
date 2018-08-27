<?php

use Illuminate\Support\Facades\Route;

Route::get('/{channel}/{secret?}', 'WebhookController@store')->name('webhook');
Route::post('/{channel}/{secret?}', 'WebhookController@store')->name('webhook');
