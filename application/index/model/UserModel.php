<?php
/**
 * Created by PhpStorm.
 * User: hitoTright
 * Date: 2017/11/1
 * Time: 11:28
 */

namespace app\index\model;

use think\Db;
use think\Exception;
use think\Session;
use think\Log;
use app\common\Utility;

class UserModel extends Base
{

    protected $searchColumn = [
        'user_name' => ['u.user_name', 'like'],
        'ps_id' => ['u.ps_id', '='],
        'phone' => ['u.phone', '='],
    ];


    public function getListCommon($param)
    {
        $query = Db::table('user u')
            ->join('position p', 'p.ps_id = u.ps_id', 'left')
            ->join('department dp', 'dp.dp_id = u.dp_id', 'left')
            ->field('u.user_id,u.user_name,u.login_name,u.email,u.phone,u.last_login,p.ps_id,p.ps_name,u.add_time,dp.dp_name')
            ->where('u.status', '=', 1);
        $this->checkSearch($query, $param['search']);
        $data = $this->autoPaginate($query, $param['paginate'])->toArray();
        return $data;
    }


    public function checkUnique($login_name,$expire_id=0){
        $count = Db::table("user")
            ->where('login_name','=',$login_name)->where('user_id','<>',$expire_id)->count();
        return $count >0?false:true;
    }

    public function add($post)
    {
        $array_insert = ['user_name' => $post['user_name'], 'phone' => $post['phone'], 'login_name' => $post['login_name'],
            'password' => md5($post['password']), 'status' => 1, 'email' => $post['email'], 'add_time' => date('Y-m-d H:i:s'),
            'ps_id' => $post['ps_id'],'dp_id'=>$post['dp_id']];
        $id = Db::table("user")->insertGetId($array_insert);
        return $id > 0 ? $id : 0;
    }

    public function edit($post)
    {
        unset($post['ps_name']);
        $post['update_time'] = date('Y-m-d H:i:s');
        Db::table('user')->where('user_id', $post['user_id'])->update($post);
    }

    public function deleteById($id)
    {
        try {
            Db::table('user')->where('user_id', '<>', SUPER_USER_ID)->where('user_id', '=', $id)->delete();
        } catch (Exception $exception) {
            $this->setMessage('删除失败');
            return false;
        }
        return true;
    }


    public function login($login_name, $password)
    {
        $user = Db::table('user')->where('login_name', $login_name)->where('status', 1)->find();
        $is_user = !empty($user) && $user['password'] === md5($password);
        if ($is_user) {
            $this->setLoginSession($user);
            Db::table('user')->where('user_id', $user['user_id'])->where('status', 1)->update(['last_login' => date('Y-m-d H:i:s')]);
            $result['user_name'] = $user['user_name'];
            $result['ps_name'] = Db::table('position')->where('ps_id', '=', $user['ps_id'])->value('ps_name', '');
            return $result;
        }
        return false;
    }

    public function setLoginSession($user)
    {
        Session::set('org_user_id', $user['user_id']);
        Session::set('org_user_name', $user['user_name']);
        Session::set('org_ps_id', $user['ps_id']);
    }

    public function logout()
    {
        Session::delete('org_user_id');
        Session::delete('org_user_name');
        Session::delete('org_ps_id');
        Session::delete('is_super_user');
        session(null);
    }

    public function getInfoById($user_id)
    {
        return Db::table('user u')
            ->join('position p', 'u.ps_id=p.ps_id', 'left')
            ->field('u.user_id,u.user_name,u.login_name,u.email,u.phone,u.last_login,p.ps_id,p.ps_name')
            ->where('u.user_id', '=', (int)$user_id)->find();
    }
}
