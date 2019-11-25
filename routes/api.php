<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->resource('galleries', 'API\GalleryController')->only([
    'index', 'show'
]);

Route::post('/register', 'API\AuthController@register')->name('api.register');
Route::post('/login', 'API\AuthController@login')->name('api.login');
