<?php


namespace app\admin\model;


use think\Db;

class Logs extends Base
{
    protected $autoWriteTimestamp = 'datetime';

    /**
     * 写入日志
     * @param $name 操作者
     * @param $action 动作
     */
    public function wirte_logs($name,$action)
    {
        $time =  date('Y-m-d H:i:s',time());
        $data['name'] = $name;
        $data['logs'] = $action;
        $data['create_time'] = $time;
        Db::name('logs')->insert($data);
    }

    /**
     * 获取日志条数
     * @return 日志条数
     */
    public function get_log_data_count()
    {
        return Db::name('logs')->count();
    }

    /**
     *获取日志列表
     */
    public function get_log_data($search)
    {
        $where = '';
        if(isset($search['name'])){
            $where = 'name like \'%'.$search['name'].'%\'';
        }
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = isset($_GET['limit']) ? $_GET['limit'] : 10;
        $limit        = " limit " . ($page - 1) * $offset . "," . $offset;

        $res = Db::name('logs')->where($where)->order('id desc')->limit($limit)->select();
        return $res;
    }


}