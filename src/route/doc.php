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

Route::get('doc/search', "\\Api\\Doc\\Apidoc@search");
Route::get('doc/list', "\\Api\\Doc\\Apidoc@getList");
Route::get('doc/info', "\\Api\\Doc\\Apidoc@getInfo");
Route::any('doc/debug', "\\Api\\Doc\\Apidoc@debug");
Route::any('doc/pass', "\\Api\\Doc\\Apidoc@pass");
Route::any('doc/login', "\\Api\\Doc\\Apidoc@login");
Route::any('doc', "\\Api\\Doc\\Apidoc@index");