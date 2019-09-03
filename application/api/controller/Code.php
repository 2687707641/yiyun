<?php


namespace app\api\controller;


class Code extends Common
{
    public function test($id)
    {
        echo json_encode($id);
    }
}