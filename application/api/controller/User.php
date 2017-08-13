<?php
/**
 * Created by PhpStorm.
 * User: hitoTright
 * Date: 2017/8/9
 * Time: 21:21
 */
namespace app\api\controller;

use app\api\model\Users;
use think\Cache;
use think\Log;

class User extends Base
{
    protected $checkLogin = true;
    public function login()
    {
        $mdl_users = new Users();
        $mdl_users->select(['user_name'=>input('param.user_name')]);
        $user=$mdl_users->find();
        if($user['password']===input('param.password')){
            session('user',$user);
            return json($user);
        }else{
            return json(['error']);
        }
    }

    public function test()
    {
        //Cache::set('user',session('user.password'));
        return json( Cache::get('user'));
    }

    public function index()
    {
        $mdl_users=new Users();
        return json($mdl_users->paginate());
    }
}