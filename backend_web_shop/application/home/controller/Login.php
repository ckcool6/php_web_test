<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Login extends Controller
{
    //
    public function register(Request $request)
    {
        if ($request->method() == 'GET') {
            // 渲染页面
            return view();
        } else if ($request->method() == 'POST') {
            // 处理数据, 新增入库
            $data = $request->param();

            $rules = [
                'phone' => 'require|regex:\d{11}|unique:user',
                'code' => 'require|regex:\d{4}',
                'password' => 'require|length:6,16|confirm:repassword'
            ];

            $msg = [
                'phone.require' => '手机号不能为空',
                'phone.regex' => '手机号格式不正确',
                'phone.unique' => '手机号已被注册',
                'code.require' => '验证码不能为空',
                'code.regex' => '验证码格式不正确',
                'password.require' => '密码不能为空',
                'password.length' => '密码长度必须在6~16个字符',
                'password.confirm' => '两次密码输入不一致',
            ];

            $valid = new \think\Validate($rules, $msg);

            if (!$valid->check($data)) {
                //验证不通过
                $this->error($valid->getError());
            }

            $user['salt'] = generate_salt();
            $user['password'] = encrypt_password($data['password'], $user['salt']);
            \think\Db::table('tb_user')
                ->insert($user);

            $this->success('注册成功', '/shop/backend_web_shop/public/home/login/login');
        }

    }

    public function login(Request $request)
    {
        if ($request->method() == 'GET') {
            // 渲染页面
            return view();
        } else if ($request->method() == 'POST') {
            // 处理数据
            $data = $request->param();

            // 验证密码
            $user = \think\Db::table('tb_user')
                ->where('phone', $data['phone'])
                ->find();

            if ($user) {
                if ($user['password'] == encrypt_password($data['password'], $user['salt'])) {
                    session('phone', $data['phone']);
                    $this->success('登录成功', '/home/index/index');
                } else {
                    $this->error('密码不正确', 'home/login/login');
                }
            } else {
                // 手机号不存在
                $this->error('手机号不存在, 请先注册', '/home/login/register');
            }
        }
    }

    public function logout()
    {
        // 清除session（当前作用域）
        session(null);

        $this->redirect('/home/login/login');
    }

    public function sendCode(Request $request)
    {
        // 读取配置文件
        $config = config('alidayu');
        // 实例化对象
        $send = new \Aliyun\Send($config);
        // 生成4位随机数
        $code = mt_rand(1000, 9999);

        $data = [
            'code' => $code,
            'product' => 'xxx'
        ];
        $mobile = $request->phone;

        // 发送数据
        $res = $send->sendSms($mobile, $data);

        // 判断结果
        if ($res === true) {
            return json(['status' => 200, 'msg' => '发送成功', 'data' => $code]);
        } else {
            return json(['status' => 500]);
        }
    }
}
