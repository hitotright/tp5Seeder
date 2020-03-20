<?php
/**
 * Created by PhpStorm.
 * User: hitotright
 * Date: 2018/5/17
 * Time: 11:12
 */

namespace app\index\behavior;

use app\common\Utility;
use think\Config;
use think\Log;
use think\Request;
use think\Session;

class AutoView
{
    public function run(&$dispatch){
        //自动跳转
        $redirect ='';
        $request = Request::instance();
        $d=$request->domain();
        $url = $request->url(true);
//        if(substr($d,0,5)!='https'&&$request->pathinfo() != 'robots.txt'){
//            $redirect = str_replace('http','https',$url);
//        }
        if($redirect !=''){
            redirect($redirect,'',301)->send();
        }
    }
}