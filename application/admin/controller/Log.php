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
        $msg = '';
        $log = new Logs();
        do{
            $search = isset($_GET['where']) ? $_GET['where'] : ""; //附加条件
            if(!isset($_GET['page']) || !isset($_GET['limit'])){
                $msg = '参数错误';
                break;
            }
            $res = $log->get_log_data($search);
            $count = $log->get_log_data_count();
        }while(false);
        $data = array(
            'code' => 0,
            'msg' => $msg,
            'data' => $res,
            'count' => $count,
        );
        echo json_encode($data);
    }
}