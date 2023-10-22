<?php

namespace app\home\controller;

// 1.引入基类Controller
use think\Controller;

// 2.继承基类
class Index extends Controller
{
    public function index()
    {
        // 1.查询所有的分类数据
        $cates = \think\Db::table('tb_category')
            ->select();
        // 2.分配到视图中
        $this->assign('cates', $cates);

        // 3. 调用基类的fetch方法
        return $this->fetch();
    }
}
