<?php


namespace app\admin\controller;

use think\Session;
use app\admin\model\Manager as ManagerModel;

class Admin extends \think\Controller
{
    /***
     * 清空session
     */
    public function clear_session()
    {
        Session::clear();
    }

    /***
     * 登录页面
     */
    public function login()
    {
        if($this->request->isPost()){
            //验证
            $rule = [
                ['username','length:5,32','账号必须在5~32字符之间'],
                ['password','length:6,32','密码必须在6~32字符之间'],
            ];
            $msg = $this->validate($this->request->post(),$rule);
            if ($msg !== true) {
                $this->error($msg, '');
            }
            $manager = new ManagerModel();
            $res = $manager->login($this->request->post('username'),$this->request->post('password'));
            if($res === false){
                $this->error($manager->getError(),'');
            }else{
                $this->success('登录成功','');
            }
        }else{
            return $this->fetch();
        }
    }

}