<?php
/**
 * Created by PhpStorm.
 * User: hitoTright
 * Date: 2017/8/9
 * Time: 21:21
 */
namespace app\api\controller;

use app\api\model\Users;
use think\Log;

class User extends \think\Controller
{
    public function login()
    {
        $mdl_users = new Users();
        $mdl_users->data([
           'user_name'=>'ll',
            'password'=>'lll',
            'age'=>11
        ]);
        $mdl_users->save();
        return json(['name'=>'hito','age'=>27,'hash'=>'abcde']);
    }

    public function index()
    {
        $mdl_users=new Users();
        $total=input('param.total',false);
        if($total){
            $total=(int)$total;
        }
        return json($mdl_users->paginate(input('param.limit',null),$total,['page'=>input('param.page',1)]));
    }
}