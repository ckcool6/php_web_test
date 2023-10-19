<?php

namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Request;

class Spec extends Controller
{
    public function index(Request $request, $cid)
    {
        $data = Db::table('tb_specification')
            ->where('category_id', $cid)
            ->value('specifications');

        return json($data);
    }


}
