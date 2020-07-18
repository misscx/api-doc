# api-doc

### 使用方法
#### 1、安装扩展
```
composer require misscx/api-doc
```

#### 2、静态资源复制
- 将本插件目录src/apidoc复制到的到网站静态资源目录内，如将apidoc目录复制到网站根目录的/static下，目录结构如下：
/static/apidoc

#### 3、配置参数
安装好扩展后在 app\config\ 文件夹下会生成 doc.php 配置文件，打开配置文件修改如下内容：
- 修改静态资源所在位置
```
'static_path' => '/static/apidoc',//静态资源所在位置
```
- 在controller参数中添加需要生成文档的类
```
    'controller' => [
        'app\\controller\\Demo' //这个是控制器的命名空间+控制器名称
    ]
```
- 配置路由
将本扩展src目录内route文件夹中的路由配置文件doc.php复制到路由目录内，如：/route/doc.php。

#### 4、注释举例
- 插件目录下有个Demo.php，可将其放入app/controller/下,单应用模式下有效，对于多应用模式，请根Demo.php进行修改。
- Demo.php文件如下：返回参数支持数组及多维数组
```
<?php
namespace app\controller;

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
     * @url /demo
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
     * @url /demo/login
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
```
#### 5、访问地址

在浏览器访问http://你的域名/doc 或者 http://你的域名/index.php/doc 查看接口文档

#### 6、扩展演示地址

演示地址：[http://cyadmin.mychunyan.com/doc](http://cyadmin.mychunyan.com/doc)

### 联系作者

作者博客地址：[http://huikon.cn](http://huikon.cn "博客")

QQ：331349451

QQ群：254929907

### 其它项目

CYAdmin：[https://gitee.com/hanchuan/cycms](https://gitee.com/hanchuan/cycms)

CYAdmin的演示地址：[http://cyadmin.mychunyan.com/](http://cyadmin.mychunyan.com/)

### 特别鸣谢

本扩展基于weiwei/api-doc ThinkPHP5.0改造而来，本人非常喜欢原扩展，无奈ThinkPHP6.0发布以来原作者一直未更新该扩展，本人经修改后发布出来，在此对原作者表示由衷感谢和敬意！

原扩展在地址：[https://github.com/zhangweiwei0326/api-doc](https://github.com/zhangweiwei0326/api-doc)
