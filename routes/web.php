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


//首页
Route::get('/','StaticPagesController@home')->name('home');

Route::get('/help','StaticPagesController@help')->name('help');

Route::get('/about','StaticPagesController@about')->name('about');

//注册页面
Route::get('signup', 'UsersController@create')->name('signup');

//执行注册及用户详情
Route::resource('users','UsersController');

//登录页面
Route::get('login','SessionsController@create')->name('login');

//登录
Route::post('login','SessionsController@store')->name('login');

//退出登录
Route::delete('logout','SessionsController@destroy')->name('logout');
