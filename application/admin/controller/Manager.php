<?php


namespace app\admin\controller;

use app\admin\model\Logs;
use app\admin\model\Manager as ManagerModel;
use app\admin\model\AuthGroup;

class Manager extends Base
{
    /***
     * 列表
     */
    public function lists()
    {
        $manager = new ManagerModel();
        $lists = $manager->where($this->_map)->paginate($this->_row,false,$this->_param);
        $auth_group =new AuthGroup();
        $group = $auth_group->where('status', 0)->field('id,name')->select();
        foreach ($lists as $k => $v) {
            if ($v['role'] == 0) {
                $lists[$k]['role'] = '超级管理员';
            } else {
                foreach ($group as $k1 => $v1) {
                    if ($v1['id'] == $v['role']) {
                        $lists[$k]['role'] = $v1['name'];
                    }
                }
            }
        }
        //条件回显
        if(isset($this->_map['username'])){
            $this->_map['username'] = trim($this->_map['username'][1],'%');
        }
        $this->assign('map',$this->_map);
        $this->assign('lists', $lists);
        return $this->fetch();
    }

    /***
     * 通用添加修改
     */
    public function _edit($id = '')
    {
        if($this->request->isPost()){
            //检查参数
            $rule =[
                ['username','length:5,10|unique:manager','账号长度在5~10字符之间|该账号已存在!'],
            ];
            $msg = $this->validate($this->_param, $rule);
            if($msg !== true){
                $this->error($msg, '');
            }
            //添加/修改数据库信息
            $manager = new ManagerModel();
            //加密密码
            $this->_param['password'] = md5($this->_param['password']);
            if($id){
                $res = $manager->edit($this->_param,['id'=>$id]);
            }else{
                $res = $manager->add($this->_param);
            }
            if($res !== false){
                //生成操作记录
                $log = new Logs();
                $log->wirte_logs($this->_user['username'],$id  ? '修改管理员:'.$this->_param['username'] : '添加管理员:'.$this->_param['username']);
                $this->success($id ? '修改成功!' : '添加成功', url('lists'));
            }else{
                $this->error($manager->getError(), '');
            }
        }
        //展示可用角色列表
        $auth = new AuthGroup();
        $lists = $auth->where('status', 0)->field('id,name')->select();
        $this->assign('lists', $lists);
        if($id){
            $manager = new ManagerModel();
            $info = $manager->where('id',$id)->find();
            $this->assign('info',$info);
        }
        return $this->fetch('edit');
    }

    public function add()
    {
        return $this->_edit();
    }

    public function edit($id)
    {
        return $this->_edit($id);
    }

    /***
     * 修改管理员状态
     * @param $id
     * @param $status
     */
    public function change_stuatus($id,$status)
    {
        $manager = new ManagerModel();
        $res = $manager->where('id',$id)->update(['status' => $status]);
        if($res !== false){
            //生成操作记录
            $log = new Logs();
            $info = $manager->where('id',$id)->field('username')->find();
            $log->wirte_logs($this->_user['username'],$status == 0 ? '启用管理员:'.$info['username'] : '禁用管理员:'.$info['username']);
            $this->success($status == 0 ? '启用成功' : '禁用成功', url('lists'));
        } else {
            $this->error($status ==  0 ? '启用失败' : '禁用失败', '');
        }

    }

    /**
     * 删除(支持批量多个id以逗号分隔)
     * @param  $id
     */
    public function del($id)
    {
        $res = ManagerModel::destroy($id);
        if($res !== false){
            //生成操作记录
            $log = new Logs();
            $log->wirte_logs($this->_user['username'],'删除管理员ID:'.$id);
            $this->success('删除成功!','lists');
        }else{
            $this->error('删除失败!','');
        }
    }


}