<?php


namespace app\admin\controller;

use app\admin\model\AuthGroup;
use app\admin\model\AuthRule;
class Role extends Base
{
    public function lists()
    {
        $auth = new AuthGroup();
        $lists = $auth->where($this->_map)->paginate($this->_row,false,$this->_param);
        //条件回显
        if(isset($this->_map['name'])){
            $this->_map['name'] = trim($this->_map['name'][1],'%');
        }
        $this->assign('map',$this->_map);
        $this->assign('lists',$lists);
        return $this->fetch();
    }

    /***
     * 通用添加更新
     */
    public function _edit($id='')
    {
        if($this->request->isPost()){
            //检查参数
            $rule = [
                ['name','unique:auth_group','该角色已存在']
            ];
            $msg = $this->validate($this->_param, $rule);
            if($msg !== true){
                $this->error($msg, '');
            }
            if (!empty($this->_param['rules'])) {
                $this->_param['rules'] = ',' . implode(',', $this->_param['rules']) . ',';
            }
            $auth = new AuthGroup();
            //添加/修改数据库信息
            if($id){
                $res = $auth->edit($this->_param,['id'=>$id]);
            }else{
                $res = $auth->add($this->_param);
            }
            if($res !==false){
                //生成操作记录
                //...
                $this->success($id ? '修改成功':'添加成功','lists');
            }else{
                $this->error($auth->getError(), '');
            }

        }

        //获取权限菜单
        $role_id = $_SESSION['think']['user_auth']['role'];
        //获取管理员所属角色权限
        $group = new AuthGroup();
        $auth = new AuthRule();
        if($role_id != 0){
            $auth_rules = $group->where('id',$role_id)->column('rules');
            $rules = explode(',',$auth_rules[0]);
            $lists = $auth->where('id','in',$rules)->select();
        }else{//超级管理员所有栏目
            $lists = $auth->select();
        }
        $this->assign('lists',$lists);
        if($id){
            $info = AuthGroup::get($id)->toArray();
            $info['rules'] = explode(',',$info['rules'] );
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
     * 删除角色(支持批量删除)
     */
    public function del($id)
    {
        $res = AuthGroup::destroy($id);
        if($res !== false){
            $this->success('删除成功','lists');
        }else{
            $this->error('删除失败','');
        }
    }

}