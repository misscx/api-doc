<?php
use think\facade\Route;

Route::get('doc/search', "\\Api\\Doc\\Apidoc@search");
Route::get('doc/list', "\\Api\\Doc\\Apidoc@getList");
Route::get('doc/info', "\\Api\\Doc\\Apidoc@getInfo");
Route::any('doc/debug', "\\Api\\Doc\\Apidoc@debug");
Route::any('doc/pass', "\\Api\\Doc\\Apidoc@pass");
Route::any('doc/login', "\\Api\\Doc\\Apidoc@login");
Route::any('doc', "\\Api\\Doc\\Apidoc@index");
