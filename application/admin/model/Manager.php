<?php

namespace app\admin\model;

class Manager extends Base
{
    protected $autoWriteTimestamp = 'datetime';

    public function login($username, $password) {
        $info = $this->get(['username' => $username]);
        if (empty($info) || $info['status'] != 0) {
            $this->error = '用户不存在或被禁用!';
            return false;
        }
        $info = $info->toArray();
        if ($info['password'] !== md5($password)) {
            $this->error = "密码错误！";
            return false;
        }
        //更改用户登录次数
        $info['login'] = $info['login'] + 1;
        $log = new Logs();
        $log->wirte_logs($username,'登录后台系统 '.'IP地址:'.request()->ip());
        $this->edit(['login'=>$info['login'],'last_ip'=>request()->ip(),'last_login_time'=>date('Y-m-d H:i:s',time())],['id'=>$info['id']]);
        $this->after_login($info);
        return $info;
    }

    /**
     * 保存登录状态
     * @param  $info
     */
    private function after_login($info) {
        unset($info['password']);
        session('user_auth', $info);
    }
}