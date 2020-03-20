<?php
/**
 * Created by PhpStorm.
 * User: hitotright
 * Date: 2019/4/8
 * Time: 18:39
 */

namespace app\index\controller\common;


use app\index\controller\Base;
use app\index\model\BceModel;
use app\index\model\File;
use think\Log;

class Upload extends Base
{
    protected function checkLogin(){
        $origin = $this->request->header('Origin');
        if(strpos($origin,'duxiaozhi.cn') !== false){
            header("Access-Control-Allow-Origin:*");
        }else{
            header("Access-Control-Allow-Origin:https://www.duxiaozhi.cn");
        }
        header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE'); //支持的http动作
        header('Access-Control-Allow-Headers:origin,x-requested-with,content-type');  //响应头
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
            exit;
        }
    }

    public function image(){
        $file_mdl = new File();
        $imageUrl = $file_mdl->image('file');
        if ($imageUrl) {
            return $this->jsonReturn($imageUrl);
        } else {
            return $this->jsonReturn('',1,$file_mdl->getError());
        }
    }

    public function video(){
        $get = $this->request->get();
        $file_mdl = new File();
        $imageUrl = $file_mdl->video('file');
        if ($imageUrl) {
            return $this->jsonReturn($imageUrl,0,$get);
        } else {
            return $this->jsonReturn('',1,$file_mdl->getError());
        }
    }

    public function word(){
        $file_mdl = new File();
        $imageUrl = $file_mdl->word('file');
        if ($imageUrl) {
            return $this->jsonReturn($imageUrl);
        } else {
            return $this->jsonReturn('',1,$file_mdl->getError());
        }
    }

    public function videoAck(){
        $model = new BceModel();
        $bec = $model->getSignature();
        $res = [
            'statusCode'=>200,
            'signature'=>$bec['sessionToken'],
            'xbceDate'=>$bec['expiration']
        ];
        jsonp($res)->send();
        exit;
    }

    public function apk(){
        $file_mdl = new File();
        $imageUrl = $file_mdl->apk('file');
        if ($imageUrl) {
            return $this->jsonReturn($imageUrl);
        } else {
            return $this->jsonReturn('',1,$file_mdl->getError());
        }
    }

}