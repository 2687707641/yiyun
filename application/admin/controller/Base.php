<?php


namespace app\admin\controller;

use app\admin\model\AuthGroup;
use app\admin\model\AuthRule;
use think\Controller;

/***
 * 控制器基类
 * Class Base
 * @package app\admin\controller
 */
class Base extends Controller
{
    //定义公用参数
    protected $_user = []; //当前登录对象
    protected $_param = []; //post参数及get参数
    protected $_map = []; //get参数
    protected $_row    = 10; //每页条数


    //初始化加载
    public function _initialize()
    {
        $this->_user = is_login();
        $this->_param = $this->request->param();
        $this->get_data();
        $this->get_meau();
        //传基本数据到每个页面
        $this->assign('user', $this->_user); //当前登录用户
        $this->assign('_self_', $this->request->url()); //当前页面对应的控制器
        $this->assign('empty', '<tr><td colspan="20" style="text-align:center;">暂无数据!</td></tr>');
    }

    /***
     * 获取左侧菜单
     */
    private function get_meau()
    {
        //获取登录管理员信息
        $role_id = $_SESSION['think']['user_auth']['role'];
        //获取管理员所属角色权限
        $group = new AuthGroup();
        $auth = new AuthRule();
        if($role_id != 0){
            $auth_rules = $group->where('id',$role_id)->column('rules');
            $rules = explode(',',$auth_rules[0]);
            $menu = $auth->where('id','in',$rules)->select();
        }else{//超级管理员所有栏目
            $menu = $auth->cateTree();
        }
        //查询权限中的栏目
        $this->assign('menu',$menu);
    }

    /***
     * 获取数据
     */
    public function get_data()
    {
        //获取兼容路由参数
        $get_data = array_merge($this->request->param(), $this->request->route());
        foreach ($get_data as $k => $v) {
            if (!is_numeric($v) && empty($v)) {
                continue;
            }
            switch ($k) {
                case "order":
                    strpos($v, "-") > -1 ? $v = str_replace("-", " ", $v) : $v = $v . " desc";
                    $this->_order             = $v;
                    break;
                case "username":
                    $this->_map[$k] = array("like", "%" . $v . "%");
                    break;
                case "p":
                    $this->_p = $v;
                    break;
                case "row":
                    $this->_row = $v;
                    break;
                case "name":
                    $this->_map[$k] = array("like", "%" . $v . "%");
                    break;
//                case "debug":
//                    defined("DEBUG") || define("DEBUG", $v);
//                    break;
                case "start":
                    $this->_btw[0]                             = $v;
                    $this->_map['create_time'] = ['between', $this->_btw];
                    break;
                case "end":
                    $v = str_replace("+"," ",$v);
                    $this->_btw[1]                             = $v;
                    $this->_map['create_time'] = ['between', $this->_btw];
                    break;
                default:
                    $this->_map[$k] = $v;
                    break;
            }
        }
    }

}