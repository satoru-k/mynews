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
    return view('welcome');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::get('news/create', 'Admin\NewsController@add');
    Route::post('news/create', 'Admin\NewsController@create');   //追記14
    Route::get('profile/create', 'Admin\ProfileController@add'); //課題10-4
    Route::post('profile/create', 'Admin\ProfileController@create'); //課題14-3
    Route::get('profile/edit', 'Admin\ProfileController@edit');  //課題10-4
    Route::post('profile/edit', 'Admin\ProfileController@update'); //課題14-6
});
/* group化せずに、19〜24行目の「;」の前に「->middleware('auth')」と記述し、
   リダイレクト先を個別に指定してもok */

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
