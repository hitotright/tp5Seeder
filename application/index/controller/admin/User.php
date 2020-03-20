<?php

namespace app\index\controller\admin;


use app\index\controller\Base;
use app\index\model\UserModel;
use think\Db;
use think\Log;
use think\Session;

class User extends Base
{
    public function index()
    {
        $model = new UserModel();
        $result = $model->getListCommon($this->request->param());
        return $this->jsonReturn($result);
    }

    public function add()
    {
        $post = $this->request->post();
        $pass = $this->validate($post, [
            'user_name|员工姓名' => 'require|min:2|max:30',
            'login_name|登录名' => 'require|min:3|max:30',
            'password|密码' => 'require|min:6|max:18',
            'email|邮箱' => 'email',
            'phone|电话' => 'number',
        ]);
        if (true !== $pass) {
            return $this->jsonReturn('', 1, $pass);
        }
        $model = new UserModel();
        if(!$model->checkUnique($post['login_name'])){
            return $this->jsonReturn('',1,'登录名已存在');
        }
        $id = $model->add($post);
        return $this->jsonReturn($id);
    }

    public function edit()
    {
        $post = $this->request->post();
        unset($post['sc_name']);
        $pass= $this->validate($post,[
            'user_id|员工编号' => 'require|number',
            'user_name|员工姓名'=>'require|min:2|max:30',
            'login_name|登录名'=>'require|min:2|max:30|unique:user',
            'email|邮箱'=>'email',
            'phone|电话' => 'number',
        ]);
        if(true !== $pass){
            return $this->jsonReturn('',1,$pass);
        }
        $model = new UserModel();
        if(!$model->checkUnique($post['login_name'],$post['user_id'])){
            return $this->jsonReturn('',1,'登录名已存在');
        }
        $this->unsetPost($post,["user_id","user_name","login_name","email","phone","ps_id","dp_id"]);
        $model->edit($post);
        return $this->jsonReturn('');
    }

    public function delete()
    {
        $post = $this->request->post();
        $pass = $this->validate($post, [
            'user_id|员工编号' => 'require|number',
        ]);
        if (true !== $pass) {
            return $this->jsonReturn('', 1, $pass);
        }
        $model = new UserModel();
        $model->deleteById($post['user_id']);
        return $this->jsonReturn('');
    }


    /**
     * @return \think\response\Json
     */
    public function getOne()
    {
        $post = $this->request->post();
        $pass = $this->validate($post, [
            'user_id|员工编号' => 'require|number',
        ]);
        if (true !== $pass) {
            return $this->jsonReturn('', 1, $pass);
        }
        $model = new UserModel();
        $res = $model->getInfoById($post['user_id']);
        return $this->jsonReturn($res);
    }
    public function ps_select()
    {
        $res = Db::table('position')->where('ps_id','<>',1)->field('ps_id as value,ps_name as label')->select();
        foreach ($res as &$res_item){
            $res_item['value'] = (int)$res_item['value'];
        }
        return $this->jsonReturn($res);
    }
    public function editPwd()
    {
        $post = $this->request->post();
        $pass= $this->validate($post,[
            'newPassword1|新密码'=>'require|min:2|max:30',
            'newPassword2|再次输入的密码'=>'require|confirm:newPassword1',
            'user_id|用户编号'=>'require|number',
        ]);
        if(true !== $pass){
            return $this->jsonReturn('',1,$pass);
        }

        Db::table('user')->where('user_id',$post['user_id'])->update(['password'=>md5($post['newPassword1'])]);
        return $this->jsonReturn('');
    }
    public function super_login(){
        $user_id = $this->request->get('id',0);
        $UserModel = new UserModel();
        //返回超级管理员
        if(Session::has('is_super_user')){
            if($user_id == 0){
                $user = $UserModel->getInfoById(session('is_super_user'));
                $UserModel->setLoginSession($user);
                Session::delete('is_super_user');
                $res = ['user_name'=>$user['user_name'],'ps_name'=>$user['ps_name']];
                return json(['error'=>0,'msg'=>$res]);
            }else{
                return json(['error'=>1,'msg'=>'请先返回管理员！']);
            }
        }else{
            //模拟登陆
            if($user_id <=0){
                return json(['error'=>1,'msg'=>'编号错误！请刷新']);
            }
            if(session('org_user_id') == SUPER_USER_ID){
                $user = $UserModel->getInfoById($user_id);
                if (!$user){
                    return json(['error'=>1,'msg'=>'该已被删除，请联系管理员']);
                }
                session('is_super_user',session('org_user_id'));
                $UserModel->setLoginSession($user);
                $res = ['user_name'=>$user['user_name'],'ps_name'=>$user['ps_name']];
                return json(['error'=>0,'msg'=>$res]);
            }else{
                return json(['error'=>1,'msg'=>'只有管理员才可以进行模拟登录!']);
            }
        }

    }

}