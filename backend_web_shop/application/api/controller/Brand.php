<?php

namespace app\api\controller;

use app\api\model\Brands;
use think\Controller;
use think\Request;

class Brand extends Controller
{
    //
    public function page(Request $request)
    {
        // 解析GET参数, 并给默认值
        $page = $request->param('page', 1);
        $rows = $request->param('rows', 5);
        $sort = $request->param('sortBy', 'id');
        $desc = $request->param('desc', 'asc');
        $desc = ($desc == 'true') ? 'desc' : 'asc';
        $key  = $request->param('key', '');

        // 构造数据
        $total = Brands::count();
        $totalPage = ceil($total/$rows);

        $items = Brands::whereLike('name', "%{$key}%")
            ->order($sort, $desc)
            ->page($page, $rows)
            ->select();

        $data = [
            'total'=>$total,
            'totalPage'=>$totalPage,
            'items'=>$items
        ];

        // 返回
        return json($data);

    }
}
