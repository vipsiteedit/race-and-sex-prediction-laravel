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

Route::get('/', function () {
    //return view('index');
    return view('welcome');

});


Route::post('/image/upload', 'ImageController@upload')->name('image.upload');

Route::get('/image/process/{id}', 'ImageController@checkProcess')->name('image.check');


Route::get('/home', 'HomeController@index')->name('home');

//Auth::routes();

