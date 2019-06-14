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

Route::group(['prefix' => 'admin'], function() {
    Route::get('news/create', 'Admin\NewsController@add');
});

//課題10-3
Route::get('XXX', 'AAAController@bbb');
//とりあえずルーティングの設定だけ書きましたが、
//もしかして、bbbというActionを追加したAAAControllerも作った方が良いのでしょうか？

//課題10-4
Route::group(['prefix' => 'admin'], function() {
  Route::get('profile/create', 'Admin\ProfileController@add');
  Route::get('profile/edit', 'Admin\ProfileController@edit');
});
//28行目の「admin」を「profile」あるいは「admin/profile」に変えて、
//29＆30行目の「profile/」を消す。という方法はOKですか？NGですか？
