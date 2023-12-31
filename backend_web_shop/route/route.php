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
Route::get('api/item/category/list', 'api/Category/lst')->allowCrossDomain();
Route::group('api', function () {
    // 获取品牌列表
    Route::get('/item/brand/page', 'api/Brand/page');
    // 添加品牌
    Route::post('/item/brand', 'api/Brand/add');
    // 上传品牌图片
    Route::post('/upload', 'api/Brand/upload');
    Route::get('/item/brand/cates/:bid', 'api/Brand/cates');
    Route::get('/item/brand', 'api/Brand/upd');
    Route::get('/item/spec/:cid', 'api/Spec/index');
    Route::get('/item/spu/page', 'api/Goods/page');

})->allowCrossDomain();


return [

];
