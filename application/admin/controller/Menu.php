<?php


namespace app\admin\controller;

use app\admin\model\AuthGroup;
use app\admin\model\AuthRule;
use app\admin\model\Logs;
use app\admin\model\Manager as ManagerModel;

class Menu extends Base
{
    /***
     * 获取栏目列表
     */
    public function lists()
    {
        //获取登录管理员信息
        $role_id = $_SESSION['think']['user_auth']['role'];
        //获取管理员所属角色权限
        $group = new AuthGroup();
        $auth = new AuthRule();
        if($role_id != 0){
            $auth_rules = $group->where('id',$role_id)->column('rules');
            $rules = explode(',',$auth_rules[0]);
            $lists = $auth->where('id','in',$rules)->select();
        }else {//超级管理员所有栏目
            $lists = $auth->select();
        }
        $this->assign('lists',$lists);
        return $this->fetch();
    }

    /***
     * 通用添加修改
     */
    public function add()
    {
        return $this->_action();
    }

    public function edit($id)
    {
        return $this->_action($id);
    }

    public function _action($id = '')
    {
        $auth = new AuthRule();
        if($id){
            $info = $auth->where('id',$id)->find();
            $this->assign('info',$info);
        }
        if($this->request->isPost()){
            //验证参数
            $rule = [
                ['remarks','unique:auth_rule','该栏目名已存在'],
            ];
            $msg = $this->validate($this->_param,$rule);
            if($msg !== true){
                $this->error($msg,'');
            }
            //修改数据库
            if($id){
                $res = $auth->edit($this->_param,['id'=>$id]);
            }else{
                $res = $auth->add($this->_param);
            }
            if($res !== false){
                //生成操作记录
                $log = new Logs();
                $log->wirte_logs($this->_user['username'],$id ? '修改栏目:'.$this->_param['remarks']:'添加栏目:'.$this->_param['remarks']);
                $this->success($id ? '修改成功':'添加成功','');
            }else{
                $this->error($auth->getError(),'');
            }
        }
        //查询顶级栏目
        $top = $auth->where('pid',0)->select();
        $this->assign('top',$top);
        return $this->fetch('edit');
    }

    /***
     * 删除
     */
    public function del($id)
    {
        $info = AuthRule::where('pid', 'in', $id)->find();
        if (!empty($info)) {
            $this->error('删除失败!此栏目下还有子栏目~请选删除子栏目~!', '');
        }
        $info  = AuthRule::where('id',$id)->find();
        $res = AuthRule::destroy($id);
        if($res !== false){
            //生成操作记录
            $log = new Logs();
            $log->wirte_logs($this->_user['username'],'删除栏目:'.$info['remarks']);
            $this->success('删除成功!','lists');
        }else{
            $this->error('删除失败!','');
        }
    }
}