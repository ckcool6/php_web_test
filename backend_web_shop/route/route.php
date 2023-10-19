<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');
Route::get('api/item/category/list','api/Category/lst')->allowCrossDomain();
Route::get('api/item/brand/page', 'api/Brand/page')->allowCrossDomain();
// 添加品牌
Route::post('api/item/brand', 'api/Brand/add')->allowCrossDomain();
Route::post('api/upload', 'api/Brand/upload')->allowCrossDomain();


return [

];
