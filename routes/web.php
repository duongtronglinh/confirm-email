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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('dang-ky', ['uses' => 'UserController@getRegister', 'as' => 'dang-ky']);
Route::post('dang-ky', ['uses' => 'UserController@postRegister', 'as' => 'dang-ky']);
Route::get('register/verify/{code}', 'UserController@verify');

Route::post('dang-nhap', ['uses' => 'UserController@authenticate', 'as' => 'dang-nhap']);
