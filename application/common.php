<?php

// 应用公共文件

/***
 * @return mixed|string
 */
function is_login()
{
    $user = session('user_auth');
    if (empty($user)) {
        redirect('index.php/admin/admin/login.html');
    } else {
        return $user;
    }
}


/***
 * 根据角色ID查询管理员名字
 */
function getMemberRoleToString($id)
{
    $list = \app\admin\model\Manager::where('role',$id)->column('username');
    if(empty($list)){
        return '无';
    }
    return implode(',', $list);
}