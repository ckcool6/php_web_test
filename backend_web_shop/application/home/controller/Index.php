<?php

namespace app\home\controller;

// 1.引入基类Controller
use think\Controller;
// 2.继承基类
class Index extends Controller
{
    public function index()
    {
        // 3. 调用基类的fetch方法
        return $this->fetch();
    }
}
