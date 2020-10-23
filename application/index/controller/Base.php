<?php
namespace app\index\controller;

use app\index\model\PermissionModel;
use app\throttle\RateLimiter;
use app\throttle\ThrottleRequests;
use HTMLPurifier;
use HTMLPurifier_Config;
use think\Controller;
use think\Response;
use think\Session;

class Base extends Controller
{

    public $purifier;
    private $response;

    protected $noCheckPost=[];

    protected $beforeActionList = [
        'checkLogin','checkPost','rateLimit'
    ];

    protected function checkLogin()
    {
        $user_id = Session::get('org_user_id');
        if (is_null($user_id)) {
            json('登录超时',LOGIN_TIMEOUT_CODE)->send();
            exit;
        }
    }

    protected function checkPermission()
    {
        $request = $this->request;
        $menu_mdl = new PermissionModel();
        //注：不检查放行的路由,加方法checkPermission(){}加以覆盖
        $result = $menu_mdl->checkAuthorize($request);
        if (!$result) {
            json('没有权限',NO_PERMISSION_CODE)->send();
            exit;
        }
    }

    protected function checkPost(){
        $post = $this->request->post();
        $config = HTMLPurifier_Config::createDefault();
        $this->purifier = new HTMLPurifier($config);
        if(!empty($post)){
            foreach ($post as $key =>$value){
                if(is_string($value)&&!in_array($key,$this->noCheckPost)){
                    $this->request->post([$key=>$this->purifier->purify($value)]);
                }
            }
        }
    }

    protected function rateLimit(){
        $throttle = new ThrottleRequests(new RateLimiter(),Session::get('org_user_id'));
        $throttle->handle($this->request,$this->getResponse(),60,1);
    }

    protected function unsetPost(&$post,$include=[]){
        foreach ($post as $k=>$value){
            if(!in_array($k,$include)){
                unset($post[$k]);
            }
        }
    }

    public function jsonReturn($data,$error=0,$message=''){
        return $this->getResponse()->content(json_encode(['error'=>$error,'data'=>$data,'message'=>$message]));
    }

    /**
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    private function getResponse(){
        if($this->response == null){
            $this->response = Response::create();
        }
        return $this->response;
    }

}
