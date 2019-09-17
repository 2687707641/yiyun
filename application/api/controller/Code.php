<?php


namespace app\api\controller;

use qcloudsms\SmsSingleSender;

class Code extends Common
{

    /**
     *获取验证码
     */
    public function get_code()
    {
        //获取参数
        $username = $this->params['username'];
        $exist    = $this->params['is_exist'];
        //检查是否为手机号
        $this->is_phone($username);
        //获取验证码
        $this->get_code_by_phone($username,$exist);
    }

    /**
     * 检测用户是否存在,保存发送信息
     *
     * @param $username 用户手机号
     * @param $exist    是否存在数据库
     */
    public function get_code_by_phone($username,$exist)
    {
        //检查用户是否存在
        $this->check_exist($username,$exist);
        /*        检测验证码发送频率，30秒一次        */
         if(session("?" . $username . '_last_send_time')){
             if(time()-session("?" . $username . '_last_send_time')<30){
                 $this->return_msg(400,'验证码30s发送一次');
             }
         }
        //生成验证码
        $code = $this->make_code(6);
        /*        使用seesion储存验证码，方便比对(验证码前缀用手机号区分)        */
        session($username . '_code', $code);
        /*        使用session储存验证码发送时间            */
        session($username . '_last_send_time', time());
        /*        发送验证码        */
        //发送验证码
        $this->send_code($username,$code);
    }

    /**
     * 生成验证码
     *
     * @param $num 验证码位数
     * @return int 验证码
     */
    public function make_code($num)
    {
        $max = pow(10, $num) - 1; //999999
        $min = pow(10, $num - 1); //100000
        return rand($min, $max);
    }

    /**
     *发送验证码
     *
     * @param $username 电话号码
     * @param $code 验证码
     */
    public function send_code($username,$code)
    {
        /*使用腾讯云SDK发送手机验证码*/
        $message = new SmsSingleSender();
        $msg = $code.'为您此次的验证码，请于5分钟内填写。如非本人操作，请忽略本短信。';
        $result = json_decode($message->send(0,'86',$username,$msg));
        if($result->result != 0){
            $this->return_msg(400,$result->errmsg);
        }else{
            $this->return_msg(200,'验证码已发送,30秒内只能发送一次');
        }
    }
}