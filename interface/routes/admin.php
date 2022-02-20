<?php

use Illuminate\Support\Facades\Route;
use App\Admin\Controllers\IndexController;
use App\Admin\Controllers\System\LoginController;

Route::get('/', function () {
    return '1234';
});

Route::group(['middleware' => ['adminAuth']], function () {

    Route::get('index',[IndexController::class,'index']);

    Route::any('system.login/userInfo', [LoginController::class, 'userInfo']);//获取管理员信息

});

Route::any('system.login/index', [LoginController::class, 'index']);//登录




