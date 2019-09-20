<?php


namespace app\admin\controller;


use app\admin\model\Logs;

class Log extends Base
{
    /***
     * 界面
     */
    public function logs()
    {
        return $this->fetch();
    }

    /**
     * 获取日志数据
     */
    public function get_log_data()
    {

        $code = 0;
        $msg = '';
        $log = new Logs();
        do{
            $content = $_GET['name'];
            $page = $_GET['page'];
            $limit = $_GET['limit'];
            if(!isset($page) || !isset($limit)){
                $msg = '参数错误';
                break;
            }
            $arr = $log->order('id desc')->select();
        }while(false);
        $data = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $arr,
            'content' => $content,
        );
        echo json_encode($data);
    }
}