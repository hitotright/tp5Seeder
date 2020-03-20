<?php

namespace app\index\controller\admin;

use app\common\Utility;
use app\index\controller\Base;
use app\index\model\BusinessModel;
use app\index\model\UserModel;
use think\captcha\Captcha;
use think\Log;
use think\Session;

class Login extends Base
{
    private $no_captcha_ip = [
        '127.*.*.*','223.112.7.21','192.168.*.*','112.4.150.48'
    ];

    protected function checkLogin(){}

    protected function checkPermission(){}

    public function captcha(){
        $config =  [
            'codeSet'=>'0123456789',
            'fontSize'=>50,
            'length'=>4
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

    public function login()
    {
//        $ip_address =  Utility::getClientIP();
        $pass= $this->validate($this->request->post(),[
            'username|用户名'=>'require|min:3|max:30',
            'password|密码'=>'require|min:6|max:30'
        ]);
//        if(!$this->match_ip($ip_address)){
////            $validate['code|验证码']='captcha|require';
//            if(!isset($post['code'])||$post['code'] == ""||$post['code'] != Session::get('xms_admin_code')){
//                return $this->jsonReturn('',1,'验证码错误');
//            }
//        }
        if(true !== $pass){
            // 验证失败 输出错误信息
            return $this->jsonReturn('',1,$pass);
        }
        $UserModel = new UserModel();
        $user_info=$UserModel->login($this->request->post('username'),$this->request->post('password'));
        if(!$user_info){
            return $this->jsonReturn('',1,'登录失败，用户名或密码错误');
        }
        return $this->jsonReturn($user_info);
    }

    public function logout()
    {
        (new UserModel())->logout();
        return $this->jsonReturn('退出登录成功！');
    }

    private function match_ip($ip){
        $r = false;
        foreach ($this->no_captcha_ip as $preg){
            if(preg_match('/'.$preg.'/',$ip)){
                $r = true;
                break;
            }
        }
        return $r;
    }
}