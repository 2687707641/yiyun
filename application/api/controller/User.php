<?php


namespace app\api\controller;


use think\Db;

class User extends Common
{
    /**
     * 用户注册
     */
    public function register()
    {
        $data = $this->params;
        //验证是否为手机号
        $this->is_phone($data['phone']);
        //验证验证码
        $this->check_code($data['phone'],$data['code']);
        //查询用户是否存在
        $this->check_exist($data['phone'],0);
        //验证昵称是否重复
//        $this->check_nickname($data['nickname']);
        $data['password'] = md5($data['password']);
        $data['create_time'] =  date('Y-m-d H:i:s',time());
        //写入数据库
        $res = Db::name('user')->insert($data);
        if(!$res){
            $this->return_msg(400,'用户注册失败');
        }else{
            $this->return_msg(200,'用户添加成功');
        }
    }

    /**
     * 用户登录
     */
    public function login()
    {
        $data = $this->params;
        //查询用户是否存在
        $this->check_exist($data['phone'],1);
        //查询用户信息
        $user = Db::name('user')->field('id,phone,password,nickname')->where('phone',$data['phone'])->find();
        if($user['password'] !== md5($data['password'])){
            $this->return_msg(400,'用户名或密码不正确!');
        }else{
            unset($user['password']);
            $this->return_msg(200,'登录成功!',$user);
        }
    }

}