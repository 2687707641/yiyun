<?php
use think\Route;

Route::get('code/:username/:is_exist','api/code/get_code'); //获取验证码接口

/********************用户管理************************************/
Route::post('user/register','api/user/register'); //用户注册

Route::post('user/login','api/user/login'); // 用户登录

