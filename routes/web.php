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

//更改用户页面
Route::get('/users/{id}/edit','UsersController@edit')->name('users.edit');

//用户激活
Route::get('/signup/confirm/{token}','UsersController@confirmEmail')->name('confirm_email');

//显示重置密码的邮箱发送页面
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');

//邮箱发送重设链接
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

//密码更新页面
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

//执行密码更新操作
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

//微博创建  删除
Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);