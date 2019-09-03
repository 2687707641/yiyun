<?php


namespace app\api\controller;

use think\Controller;

class Common extends Controller
{

    
    public function return_msg($code, $msg = '', $data = []) {
        /*                    组合数据                    */
        $return_data['$code'] = $code;
        $return_data['$msg']  = $msg;
        $return_data['$data'] = $data;
        /*            返回错误信息，并终止脚本       */
        echo json_encode($return_data, JSON_UNESCAPED_UNICODE);die;

    }


}