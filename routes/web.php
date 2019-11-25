<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'GalleryController@showGalleries')->name('home');
Route::get('/image/{idGallery}', 'GalleryController@showImage')->name('images');

//Route::auth();
