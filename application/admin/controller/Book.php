<?php


namespace app\admin\controller;

use app\admin\model\Logs;
use app\admin\model\Book as BookModel;

class Book extends Base
{
    /***
     * 商品列表页面
     */
    public function lists()
    {
        return $this->fetch();
    }

    /***
     * 获取商品列表
     */
    public function get_book_data()
    {
        $msg = '';
        $book = new BookModel();
        do{
            //获取过滤条件
            $search = isset($_GET['where']) ? $_GET['where'] : '';
            if(!isset($_GET['page']) || !isset($_GET['limit'])){
                $msg = '参数错误';
                break;
            }
            $count = $book->get_book_count($search);
            $res = $book->get_book_data($search);
        }while(false);
        $data = array(
            'code' => 0,
            'msg' => $msg,
            'data' => $res,
            'count' => $count,
        );
        echo json_encode($data);
    }

    /***
     * 获取商品分类
     */
    public function get_book_type($id= 0)
    {
        $book = new BookModel();
        $data = $book->name('cate')->field('name,id')->where('pid',$id)->select();
        echo json_encode($data);
    }

    /***
     * 商品分类页面
     */
    public function cate()
    {
        $cate = new BookModel();
        $lists = $cate->cateTree();
        $this->assign('lists',$lists);
        return $this->fetch();
    }

    /***
     * 商品分类通用添加修改
     */
    public function cate_add()
    {
        return $this->cate_action();
    }

    public function cate_edit($id)
    {
        return $this->cate_action($id);
    }

    public function cate_action($id ='')
    {
        $cate = new BookModel();
        if($this->request->isPost()){
            //验证参数
            $rule = [
                ['name','unique:cate','该栏目名已存在'],
            ];
            $msg = $this->validate($this->_param,$rule);
            if($msg !== true){
                $this->error($msg,'');
            }
            //修改数据库
            if($id){
                $res = $cate->name('cate')->update($this->_param,['id'=>$id]);
            }else{
                $res = $cate->name('cate')->insert($this->_param);
            }
            if($res !== false){
                //生成操作记录
                $log = new Logs();
                $log->wirte_logs($this->_user['username'],$id ? '修改商品分类:'.$this->_param['name']:'添加商品分类:'.$this->_param['name']);
                $this->success($id ? '修改成功':'添加成功','');
            }else{
                $this->error($cate->name('cate')->getError(),'');
            }
        }
        //查询顶级栏目
        $lists = $cate->cateTree();
        $this->assign('lists',$lists);
        if($id){
            $info = $cate->name('cate')->where('id',$id)->find();
            $this->assign('info',$info);
        }
        return $this->fetch('cate_edit');
    }

    /***
     * 商品分类删除
     */
    public function cate_del($id)
    {
        $info = BookModel::name('cate')->where('pid', 'in', $id)->find();
        if (!empty($info)) {
            $this->error('删除失败!此栏目下还有子栏目~请选删除子栏目~!', '');
        }
        $info  = BookModel::name('cate')->where('id',$id)->find();
        $res = BookModel::name('cate')->delete($id);
        if($res !== false){
            //生成操作记录
            $log = new Logs();
            $log->wirte_logs($this->_user['username'],'删除商品分类:'.$info['name']);
            $this->success('删除成功!','');
        }else{
            $this->error('删除失败!','');
        }
    }
}