<?php

namespace app\api\controller;

use app\api\model\Cates;
use think\Controller;
use think\Request;

class Category extends Controller
{
    public function lst(Request $request)
    {
        /**
         * @ example:select * from `tb_category` where `parent_id` = 0
         * */
        $cates = Cates::field('id,name,parent_id as parentId, is_parent as isParent,sort')
            ->where('parent_id', $request->param('pid'))
            ->select();

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
}
