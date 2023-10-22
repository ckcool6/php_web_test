<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
if(!function_exists('generate_salt')) {
    //定义生成盐函数
    function generate_salt() {
        $str = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
        $salt = substr(str_shuffle($str),10,6);
        return $salt;
    }
}

if(!function_exists('encrypt_password')){
    //定义密码加密函数
    function encrypt_password($password, $salt){
        return md5( md5($password) . $salt );
    }
}

if(!function_exists('encrypt_phone')){
    //定义手机号安全函数
    function encrypt_phone($phone) {
        //13098878677 => 130****8677
        return substr($phone, 0, 3) . '****' . substr($phone, 7);
    }
}