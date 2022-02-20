<?php

use Illuminate\Support\Facades\Route;

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
    return '123';
    return view('welcome');
});

//后台路由组
//Route::prefix('admina')->get('/',function(){
//    return 'web/admina';
//});


//Route::prefix('a')->get('/',function (){
//    return '1231';
//});


