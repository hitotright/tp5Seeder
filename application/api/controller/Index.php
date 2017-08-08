<?php
namespace app\api\controller;

class Index extends \think\Controller
{
    public function index()
    {
//        $this->assign([
//            'title'=>'tp5-vue',
//            'email'=>'915840223@qq.com'
//        ]);
//        return $this->fetch('index');
        return $this->display('hello');
    }
}
