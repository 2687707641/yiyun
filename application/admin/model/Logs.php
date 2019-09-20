<?php


namespace app\admin\model;


use think\Db;

class Logs extends Base
{
    protected $autoWriteTimestamp = 'datetime';

    public function wirte_logs($name,$action)
    {
        $time =  date('Y-m-d H:i:s',time());
        $data['name'] = $name;
        $data['logs'] = $action;
        $data['create_time'] = $time;
        Db::name('logs')->insert($data);
    }
}