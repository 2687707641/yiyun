<?php


namespace app\admin\controller;

use app\admin\model\Logs;
use app\admin\model\User as Usermodel;

class User extends Base
{
    /**
     * 展示列表
     */
    public function lists()
    {
        $user = new Usermodel();
        $lists = $user->where('deleted',0)->where($this->_map)->paginate($this->_row,false,$this->_param);
        $this->assign('map',$this->_map);
        $this->assign('lists', $lists);
        return $this->fetch();
    }

    /**
     * 修改用户状态
     * @param $id 用户ID
     * @param $status 状态(改变后)
     */
    public function change_stuatus($id,$status)
    {
        $user = new Usermodel();
        $res = $user->where('id',$id)->update(['status' => $status]);
        $info = $user->where('id',$id)->field('phone')->find();
        if($res !== false){
            //生成操作记录
            $log = new Logs();
            $log->wirte_logs($this->_user['username'],$status == 0 ? '启用用户: '. $info['phone'] : '禁用用户: '. $info['phone']);
            $this->success($status == 0 ? '启用成功' : '禁用成功', url('lists'));
        } else {
            $this->error($status ==  0 ? '启用失败' : '禁用失败', '');
        }

    }

    /**
     * 删除(支持批量多个id以逗号分隔)
     * @param  $id 用户ID
     */
    public function del($id)
    {
        $res = Usermodel::destroy($id);
        if($res !== false){
            //生成操作记录
            $log = new Logs();
            $log->wirte_logs($this->_user['username'],'删除用户: ' .$id);
            $this->success('删除成功!','lists');
        }else{
            $this->error('删除失败!','');
        }
    }

}