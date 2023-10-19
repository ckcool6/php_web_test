<?php

namespace app\api\controller;

use app\api\model\Brands;
use think\Controller;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
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

    /**
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function cates(Request $request, $bid)
    {
        // 1. 根据brand_id查询中间表, 得到category_id
        $data = Db::table('tb_category_brand')
            ->field('category_id')
            ->where('brand_id', $bid)
            ->select();

        $cates = [];
        // 2. 根据category_id, 查询分类表
        foreach ($data as $v) {
            $cates[] = Db::table('tb_category')
                ->field('id, name')
                ->find($v['category_id']);
        }

        /**
         * 跨域cors
         */
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => '*',
            'Access-Control-Allow-Credentials' => 'false',
            'Access-Control-Allow-Headers' => 'content-type'
        ];

        return json($cates)->header($headers);
    }

    public function upd(Request $request)
    {
        // 1. 更新品牌表
        Brands::update($request->param());
        // 2. 更新中间表
        // 这里存在一点逻辑小bug, 当更新的分类是新添加时会怎样
        $cids = $request->param('cids');
        foreach (explode(',', $cids) as $cid) {
            Db::table('tb_category_brand')
                ->where('brand_id', $request->id)
                ->update(['category_id' => $cid]);
        }
    }
}
