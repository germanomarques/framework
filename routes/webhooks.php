<?php

use Illuminate\Support\Facades\Route;

Route::get('/{channel}/{secret?}', 'WebhookController@store')->name('fondbot.webhook');
Route::post('/{channel}/{secret?}', 'WebhookController@store')->name('fondbot.webhook');
