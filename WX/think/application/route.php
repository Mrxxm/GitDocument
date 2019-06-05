<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner'); // 首页 轮播图

Route::get('api/:version/theme', 'api/:version.Theme/getSimpleList'); // 首页 主题
Route::get('api/:version/theme/:id', 'api/:version.Theme/getComplexOne'); // 首页 主题详情页


//Route::get('api/:version/product/by_category', 'api/:version.Product/getAllInCategory');
//Route::get('api/:version/product/:id', 'api/:version.Product/getOne', [], ['id' => '\d+']);
//Route::get('api/:version/product/recent', 'api/:version.Product/getRecent');

Route::group('api/:version/product', function () {
    Route::get('/by_category', 'api/:version.Product/getAllInCategory'); // 分类 展示页
    Route::get('/:id', 'api/:version.Product/getOne', [], ['id' => '\d+']); // 首页 商品详情页
    Route::get('/recent', 'api/:version.Product/getRecent'); // 首页 最近新品
});

Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories'); // 分类 主页

Route::post('api/:version/token/user', 'api/:version.Token/getToken'); // token.js 编写地址章节中使用，一部分：实现token加载并验证
Route::post('api/:version/token/verify', 'api/:version.Token/verifyToken'); // token.js 编写地址章节中使用，一部分：重构了request方法实现token失效问题

Route::post('api/:version/address', 'api/:version.Address/createOrUpdateAddress'); // 订单页面 点击添加地址
Route::get('api/:version/address', 'api/:version.Address/getUserAddress'); // 订单页面 从购物车到订单页面时加载地址

Route::post('api/:version/order', 'api/:version.Order/placeOrder'); // 付款按钮 order-model.js doOrder方法
Route::get('api/:version/order/by_user', 'api/:version.Order/getSummaryByUser');
Route::get('api/:version/order/:id', 'api/:version.Order/getDetail', [], ['id' => '\d+']); // pay-result页面返回订单页面 order.js onShow()

Route::post('api/:version/pay/pre_order', 'api/:version.Pay/getPreOrder'); // 付款按钮 order-model.js execPay方法
Route::post('api/:version/pay/notify', 'api/:version.Pay/receiveNotify');











