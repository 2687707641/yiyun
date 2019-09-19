<?php
use think\Route;

Route::get('code/:username/:is_exist','api/code/get_code'); //获取验证码接口

/********************用户管理************************************/
Route::post('user/register','api/user/register'); //用户注册

Route::post('user/login','api/user/login'); // 用户登录

Route::post('user/change_pwd','api/user/change_pwd'); // 用户修改密码

Route::post('user/find_pwd','api/user/find_pwd');//用户找回密码

Route::post('user/change_nickname','api/user/change_nickname'); //用户修改昵称