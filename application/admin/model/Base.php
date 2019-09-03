<?php


namespace app\admin\model;

use think\Model;

/***
 * 模型基类
 * Class Base
 * @package app\admin\model
 */
class Base extends Model
{
    public $allCount         = 0;
    protected $resultSetType = 'collection';

    /**
     * 列表
     * @param  $map
     * @param  $p
     * @param  $row
     * @param string $order
     * @param  $fields
     * @return
     */
    public function lists($map, $p, $row, $order = '', $fields = true)
    {
        $lists = $this->where($map)->field($fields)->page($p, $row)->order($order)->select()->toArray();
        if (!empty($lists)) {
            $this->allCount = $this->where($map)->count();
        }
        return $lists;
    }
    /*
     * 详情
     * @param array $map
     * @param mixed   $field
     * @param string $order
     * @return bool
     */
    public function read($map, $field = '*', $order = '')
    {
        $info = $this->field($field)->where($map)->order($order)->find();
        return $info->toArray();
    }

    /*
     * 新增
     * @param array $data
     * @return bool
     */
    public function add($data)
    {
        return $this->allowField(true)->save($data);
    }

    /*
     * 条件更新
     * @param array $update
     * @param array $condition
     * @return bool
     */
    public function edit($update, $condition)
    {
        return $this->isUpdate(true)->allowField(true)->save($update, $condition);
    }
    /*
     * 更新
     * @param array $update
     * @param array $condition
     * @return bool
     */
    public function edits($update)
    {
        if(isset($update['id'])){
            return $this->isUpdate(true)->allowField(true)->save($update);
        }else{
            return $this->isUpdate(false)->allowField(true)->save($update);
        }
    }
    /*
     * 删除
     * @param array $condition
     * @return bool
     */
    public function del($condition)
    {
        return $this->destroy($condition);
    }
}