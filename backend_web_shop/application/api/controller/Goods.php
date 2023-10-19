<?php

namespace app\api\controller;

use think\Controller;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Request;

class Goods extends Controller
{
    /**
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws DataNotFoundException
     */
    public function page(Request $request)
    {
        // 1. 解析传递的参数, 并给默认值
        $page = $request->param('page', 1);
        $rows = $request->param('rows', 5);
        $key = $request->param('key', '');
        $saleable = $request->param('saleable', 'true');
        $saleable = $saleable == 'true' ? 1 : 0;

        $spus = Db::table('tb_spu')
            ->where('saleable', $saleable)
            ->whereLike('title', "%{$key}%")
            ->page($page, $rows)
            ->select();

        $items = [];

        if (!empty($spus)) {
            // 遍历数组
            foreach ($spus as $row) {
                $item['id'] = $row['id'];
                $item['title'] = $row['title'];
                // 根据cid1, cid2, cid3查询到对应的分类名
                $cname = Db::table('tb_category')
                    ->where('id', 'in', [$row['cid1'], $row['cid2'], $row['cid3']])
                    ->column('name');
                // 使用/连接分类名
                $item['cname'] = implode('/', $cname);
                // 根据brand_id 查询品牌名称
                $bname = Db::table('tb_brand')
                    ->where('id', $row['brand_id'])
                    ->value('name');
                $item['bname'] = $bname;
                $items[] = $item;
            }
        }

        $total = Db::table('tb_spu')->count();
        $totalPage = ceil($total/$rows);

        $data = [
            'total'=>$total,
            'totalPage'=>$totalPage,
            'items'=>$items
        ];

        return json($data);
    }
}