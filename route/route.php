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

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');
/*Route::get('api/:version/index', 'api/:version.Index/index');
Route::rule('api/:version/index/notify', 'api/:version.Index/index');*/

Route::get('api/:version/token/admin', 'api/:version.Token/getAdminToken');
Route::get('api/:version/token/login/out', 'api/:version.Token/loginOut');
Route::get('api/:version/token/android', 'api/:version.Token/getAndroidToken');

Route::post('api/:version/card/save', 'api/:version.Card/save');
Route::post('api/:version/card/order/save', 'api/:version.Card/saveOrder');
Route::post('api/:version/card/handel', 'api/:version.Card/handel');
Route::post('api/:version/card/update', 'api/:version.Card/update');
Route::get('api/:version/card/user/check', 'api/:version.Card/userCheck');
Route::get('api/:version/card/list', 'api/:version.Card/getList');

Route::post('api/:version/system/price/update', 'api/:version.SystemPrice/update');
Route::get('api/:version/system/price', 'api/:version.SystemPrice/getInfo');

Route::get('api/:version/pay/getPreOrder', 'api/:version.Pay/getPreOrder');
Route::rule('api/:version/pay/alipay/notify', 'api/:version.Index/index');





