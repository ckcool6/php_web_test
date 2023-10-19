<?php

namespace app\api\controller;

use app\api\model\Brands;
use think\Controller;
use think\Db;
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
        $key = $request->param('key', '');

        // 构造数据
        $total = Brands::count();
        $totalPage = ceil($total / $rows);

        $items = Brands::whereLike('name', "%{$key}%")
            ->order($sort, $desc)
            ->page($page, $rows)
            ->select();

        $data = [
            'total' => $total,
            'totalPage' => $totalPage,
            'items' => $items
        ];

        // 返回
        return json($data);

    }

    public function add(Request $request)
    {
        // 根据提交的数据, 添加到brands表中
        $brand = Brands::create($request->param());

        $cids = $request->param('cids');
        // 向关联的表category_brand中添加数据
        // cids 76, 77
        foreach (explode(',', $cids) as $cid) {
            $data = ['category_id' => $cid, 'brand_id' => $brand->id];
            Db::table('tb_category_brand')->insert($data);
        }

        return json($brand, '201');

    }

    public function upload(Request $request)
    {
        $file = $request->file('file');
        $info = $file->move('./uploads');

        $str = "http://localhost:82/shop/backend_web_shop/public/uploads/" . $info->getSaveName();
        if ($info) {
            return str_replace("\\", "/", $str);
        }
        return 0;
    }
}
