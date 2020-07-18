<?php
namespace app\index\controller;

use think\facade\Request;
/**
 * @title 测试demo
 * @description 接口说明
 * @group 接口分组
 * @header name:key require:1 default: desc:秘钥(区别设置)
 * @param name:public type:int require:1 default:1 other: desc:公共参数(区别设置)
 */
class Demo
{
    /**
     * @title 测试demo接口
     * @description 接口说明
     * @author 开发者
     * @url /index/demo
     * @method POST
     *
     * @header name:device require:1 default: desc:设备号
     *
     * @param name:id type:int require:1 default:1 other: desc:唯一ID
     *
     * @return name:名称
     * @return mobile:手机号
     * @return list_messages:消息列表@
     * @list_messages message_id:消息ID content:消息内容
     * @return object:对象信息@!
     * @object attribute1:对象属性1 attribute2:对象属性2
     * @return array:数组值#
     * @return list_user:用户列表@
     * @list_user name:名称 mobile:手机号 list_follow:关注列表@
     * @list_follow user_id:用户id name:名称
     */
    public function index()
    {
        //接口代码
        $header = Request::header();
        $data = Request::request();
        echo json_encode(["code"=>200, "message"=>"success", "data"=>['header'=>$header,'data'=>$data]]);
    }

    /**
     * @title 登录接口
     * @description 接口说明
     * @author 开发者
     * @url /index/demo/login
     * @method GET
     * @module 用户模块

     * @param name:name type:int require:1 default:1 other: desc:用户名
     * @param name:pass type:int require:1 default:1 other: desc:密码
     *
     * @return name:名称
     * @return mobile:手机号
     *
     */
    public function login()
    {
        //接口代码
        $header = Request::header();
        $data = Request::request();
        echo json_encode(["code"=>200, "message"=>"success", "data"=>['header'=>$header,'data'=>$data]]);
    }
}