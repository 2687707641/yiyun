<?php


namespace app\admin\controller;


class Order extends Base
{
    /**
     * 订单列表
     */
    public function lists()
    {
        return $this->fetch();
    }
}