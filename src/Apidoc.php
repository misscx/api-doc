<?php
namespace Api\Doc;

use think\facade\Request;
use think\facade\Config;
use think\facade\View;


class Apidoc
{
    protected $assets_path = "";
    protected $view_path = "";
    protected $root = "";

    /**
     * @var Doc 
     */
    protected $doc;
    /**
     * @var array 资源类型
     */
    protected $mimeType = [
        'xml'  => 'application/xml,text/xml,application/x-xml',
        'json' => 'application/json,text/x-json,application/jsonrequest,text/json',
        'js'   => 'text/javascript,application/javascript,application/x-javascript',
        'css'  => 'text/css',
        'rss'  => 'application/rss+xml',
        'yaml' => 'application/x-yaml,text/yaml',
        'atom' => 'application/atom+xml',
        'pdf'  => 'application/pdf',
        'text' => 'text/plain',
        'png'  => 'image/png',
        'jpg'  => 'image/jpg,image/jpeg,image/pjpeg',
        'gif'  => 'image/gif',
        'csv'  => 'text/csv',
        'html' => 'text/html,application/xhtml+xml,*/*',
    ];

    public function __construct()
    {

        $this->doc = new Doc((array) Config::get('doc'));
        $this->root = Request::root(true);
        $this->assets_path = $this->root.'/vendor/weiwei/api-doc/src/assets';
        
        View::assign('title',$this->doc->__get("title"));
        View::assign('version',$this->doc->__get("version"));
        View::assign('copyright',$this->doc->__get("copyright"));

        View::config(['view_path' =>__DIR__.'/view/']);
        View::assign('static', $this->assets_path);//模版路径

    }

    /**
     * 验证密码
     * @return bool
     */
    protected function checkLogin()
    {
        $pass = $this->doc->__get("password");
        if($pass){
            if(session('pass') === md5($pass)){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    /**
     * 显示模板
     * @param $name
     * @param array $vars
     * @param array $replace
     * @param array $config
     * @return string
     */
    protected function show($name, $vars = [], $config = [])
    {
        $vars = array_merge(['root'=>$this->root], $vars);
        return View::fetch($name, $vars, $config);
    }


    /**
     * 输入密码
     * @return string
     */
    public function pass()
    {
        return $this->show('pass');
    }

    /**
     * 登录
     * @return string
     */
    public function login()
    {
        $pass = $this->doc->__get("password");
        if($pass && Request::param('pass') === $pass){
            session('pass', md5($pass));
            $data = ['status' => '200', 'message' => '登录成功'];
        }else if(!$pass){
            $data = ['status' => '200', 'message' => '登录成功'];
        }else{
            $data = ['status' => '300', 'message' => '密码错误'];
        }
        return response($data, 200, [], 'json');
    }

    /**
     * 文档首页
     * @return mixed
     */
    public function index()
    {
        if($this->checkLogin()){
            return $this->show('index', ['doc' => Request::param('doc')]);
        }else{
            return redirect('doc/pass');
        }
    }

    /**
     * 文档搜素
     * @return mixed|\think\Response
     */
    public function search()
    {
        if(Request::isAjax())
        {
            $data = $this->doc->searchList(Request::param('query'));
            return response($data, 200, [], 'json');
        }
        else
        {
            $module = $this->doc->getModuleList();
            return $this->show('search', ['module' => $module]);
        }
    }

    /**
     * 设置目录树及图标
     * @param $actions
     * @return mixed
     */
    protected function setIcon($actions, $num = 1)
    {
        foreach ($actions as $key=>$moudel){
            if(isset($moudel['actions'])){
                $actions[$key]['iconClose'] = $this->assets_path."/js/zTree_v3/img/zt-folder.png";
                $actions[$key]['iconOpen'] = $this->assets_path."/js/zTree_v3/img/zt-folder-o.png";
                $actions[$key]['open'] = true;
                $actions[$key]['isParent'] = true;
                $actions[$key]['actions'] = $this->setIcon($moudel['actions'], $num = 1);
            }else{
                $actions[$key]['icon'] = $this->assets_path."/js/zTree_v3/img/zt-file.png";
                $actions[$key]['isParent'] = false;
                $actions[$key]['isText'] = true;
            }
        }
        return $actions;
    }

    /**
     * 接口列表
     * @return \think\Response
     */
    public function getList()
    {
        $list = $this->doc->getList();
        $list = $this->setIcon($list);
        return response(['firstId'=>'', 'list'=>$list], 200, [], 'json');
    }

    /**
     * 接口详情
     * @param string $name
     * @return mixed
     */
    public function getInfo($name = "")
    {
        list($class, $action) = explode("::", $name);
        $action_doc = $this->doc->getInfo($class, $action);
        if($action_doc)
        {
            $return = $this->doc->formatReturn($action_doc);
            $action_doc['header'] = isset($action_doc['header']) ? array_merge($this->doc->__get('public_header'), $action_doc['header']) : [];
            $action_doc['param'] = isset($action_doc['param']) ? array_merge($this->doc->__get('public_param'), $action_doc['param']) : [];
            return $this->show('info', ['doc'=>$action_doc, 'return'=>$return]);
        }
    }


    /**
     * 接口访问测试
     * @return \think\Response
     */
    public function debug()
    {
        $data = Request::param();
        $api_url = Request::param('url');
        $res['status'] = '404';
        $res['meaasge'] = '接口地址无法访问！';
        $res['result'] = '';
        $method =  Request::param('method_type', 'GET');
        $cookie = Request::param('cookie');
        $headers = Request::param('header/a', array());
        unset($data['method_type']);
        unset($data['url']);
        unset($data['cookie']);
        unset($data['header']);
        $res['result'] = $this->http_request($api_url, $cookie, $data, $method, $headers);

        if($res['result']){
            $res['status'] = '200';
            $res['meaasge'] = 'success';
        }
        return response($res, 200, [], 'json');
    }
    
    /**
     * curl模拟请求方法
     * @param $url
     * @param $cookie
     * @param array $data
     * @param $method
     * @param array $headers
     * @return mixed
     */
    private function http_request($url, $cookie, $data = array(), $method = array(), $headers = array()){
        $curl = curl_init();
        if(count($data) && $method == "GET"){
            $data = array_filter($data);
            $url .= "?".http_build_query($data);
            $url = str_replace(array('%5B0%5D'), array('[]'), $url);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (count($headers)){
            $head = array();
            foreach ($headers as $name=>$value){
                $head[] = $name.":".$value;
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $head);
        }
        $method = strtoupper($method);
        switch($method) {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }
        if (!empty($cookie)){
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}