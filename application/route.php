<?php
use think\Route;

Route::get('code/:username/:is_exist','api/code/get_code'); //获取验证码接口
