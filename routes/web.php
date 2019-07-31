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
































































//前台 首页的路由
Route::get('home','Home\IndexController@index');

//前台 注册
Route::get('home/register','Home\RegisterController@index');
Route::post('home/register','Home\RegisterController@store');
Route::get('home/register/changestatus','Home\RegisterController@changeStatus');