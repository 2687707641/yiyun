<?php


namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Validate;

class Common extends Controller
{
    //定义公用参数
    protected $request; //处理请求参数
    protected $validater; //用来验证参数
    protected $params; //过滤后符合要求的参数

    //验证规则
    protected $rules = array(
        'Code' => array(
            'get_code' => array(
                'username' => 'require',
                'is_exist' => 'require|number|length:1',
            ),
        ),

        'User' => array(
            'register' => array(
//                'nickname' => 'require',
                'phone' => 'require|number',
                'password' => 'require|min:6|max:20|alphaDash',
                'code' => 'require|number|length:6',
            ),

            'login' => array(
                'phone' => 'require|number',
                'password' => 'require',
            ),

            'change_pwd' => array(
                'phone' => 'require|number',
                'user_ini_pwd' => 'require|number',
                'user_new_pwd' => 'require|min:6|max:20|alphaDash',
            ),

            'find_pwd' => array(
                'phone' => 'require|number',
                'code' => 'require|number|length:6',
                'user_new_pwd' => 'require|min:6|max:20|alphaDash',
            ),

            'change_nickname' => array(
                'id' => 'require|number',
                'nickname' => 'require|chsDash|min:3|max:6'
            ),
        ),
    );

    /**
     * 初始化函数
     */
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->request = Request::instance();
        $this->params = $this->check_params($this->request->param(true)); //过滤参数
    }

    /**
     * 返回信息
     *
     * @param $code
     * @param string $msg
     * @param array $data
     */
    public function return_msg($code, $msg = '', $data = []) {
        /*                    组合数据                    */
        $return_data['code'] = $code;
        $return_data['msg']  = $msg;
        $return_data['data'] = $data;
        /*            返回错误信息，并终止脚本       */
        echo json_encode($return_data, JSON_UNESCAPED_UNICODE);die;
    }


    /**
     * 验证参数 参数过滤
     *
     * @param  [array] $arr [除time和token外的所有参数]
     * @return [return]      [合格的参数数组]
     */
    public function check_params($arr) {
        /*            获取验证规则          */
        /*rules[控制器][方法]*/
        $rule = $this->rules[$this->request->controller()][$this->request->action()];
        /*        验证参数并返回错误        */
        $this->validater = new Validate($rule);
        if (!$this->validater->check($arr)) {
            $this->return_msg(400, $this->validater->getError());
        }
        /*        如果正确，通过验证        */
        return $arr;
    }

    /**
     * 验证是否为手机号
     *
     * @param $username 验证号码
     */
    public function is_phone($username) {
        $is_phone = preg_match('/^1[34578]\d{9}$/', $username);
        if (!$is_phone) {
            $this->return_msg(400, '手机号不正确!');
        }
    }

    /**
     * 验证用户是否存在
     *
     * @param $username 用户手机号
     * @param $exist    是否存在 0:不该存在 1:应存在
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function check_exist($username,$exist)
    {
        $phone_res = db('user')->where('phone', $username)->find(); //查到返回1 没有返回NULL
        switch ($exist) {
            /*         手机号不应该在数据库中        */
            case '0':
                if ($phone_res) {
                    $this->return_msg(400, '此手机号已被占用!');
                }
                break;

            /*        手机号应该在数据库中    */
            case '1':
                if (!$phone_res) {
                    $this->return_msg(400, '此手机号不存在!');
                }
                break;
        }
    }


    /**
     * 检查验证码
     *
     * @param $phone 手机号
     * @param $code 验证码
     */
    public function check_code($phone, $code) {
        /*        检查时间是否超时        */
//         $last_time = session($phone,'_last_send_time');
//         if(time() - $last_time > 300){
//             $this->return_msg(400,'验证超时，请在五分钟内验证!',$last_time);
//         }

        /*        检查验证码是否正确        */
        if (session($phone . "_code") != $code) {
            $this->return_msg(400, '验证码错误!');
        }

        /*        验证码只验证一次        */
        // session($phone.'_code',null);
    }

}