<?php


namespace app\admin\controller;

use think\Session;

class Index extends Base
{
    /***
     * 主页面
     */
    public function index()
    {
        return $this->fetch();
    }
    
    /***
     * 右侧主体部分
     */
    public function welcome()
    {
        return $this->fetch();
    }
    
}