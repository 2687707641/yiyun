<?php


namespace app\admin\controller;


class Book extends Base
{
    /**
     * 展示页面
     */
    public function lists()
    {
        return $this->fetch();
    }

}