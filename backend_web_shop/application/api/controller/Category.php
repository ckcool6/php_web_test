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
         * @ select * from `tb_category` where `parent_id` = 0
         * */
        $cates = Cates::where('parent_id',$request->pid)->select();

        return json($cates);
    }
}
