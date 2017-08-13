<?php
namespace app\api\controller;

use think\Controller;

class Base extends Controller
{

    protected $checkLogin=false;
    protected $checkProcess=false;

    protected $beforeActionList=[
        'checkLogin','checkProcess'
    ];

    protected function checkLogin()
    {
        if($this->checkLogin&&!session('?user')){
            json(['code'=>001,'error'=>'登录超时！'])->send();
            exit();
        }
    }

    protected function checkProcess()
    {

    }
}