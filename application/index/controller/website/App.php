<?php
/**
 * Created by PhpStorm.
 * User: hitoTright
 * Date: 2018/2/1
 * Time: 14:01
 * Info: 分类列表;添加分类;删除分类
 */

namespace app\index\controller\website;

use app\index\controller\Base;
use app\index\model\AppModel;
use think\Log;
use think\Db;

class App extends Base
{
    public function index(){
        $model = new AppModel();
        $result = $model->getList($this->request->param());
        return $this->jsonReturn($result);
    }

    public function delete(){
        $model = new AppModel();
        $result = $model->delApp($this->request->param());
        return $this->jsonReturn($result);
    }

    public function add(){
        $post = $this->request->param();
        $pass = $this->validate($post, [
            "app_name|应用名称" => "require",
            "test_version|应用版本号" => "require",
            "sign_secret|签名秘钥" => "require",
        ]);
        if (true !== $pass) {
            return $this->jsonReturn('', 1, $pass);
        }
        $model = new AppModel();
        $result = $model->appInsert($post);
        return $this->jsonReturn($result);
    }

    public function upload(){
        $post = $this->request->param();
        $pass = $this->validate($post, [
            "test_version|应用版本号" => "require",
            "memo|更新说明" => "require",
        ]);
        if (true !== $pass) {
            return $this->jsonReturn('', 1, $pass);
        }
        $model = new AppModel();
        $result = $model->appUpload($post);
        return $this->jsonReturn($result);
    }
    public function getAppForEdit(){
        $post = $this->request->post();
        $pass = $this->validate($post, [
            'app_id' => 'require',
        ]);
        if (true !== $pass) {
            return $this->jsonReturn('', 1, $pass);
        }
        $app_id = $post['app_id'];
        $data = Db::table('app')
            ->where('app_id',$app_id)
            ->field('app_id,app_name,mini_up_version')
            ->find();
        return $this->jsonReturn($data);
    }

    public function edit(){
        $post = $this->request->param();
        $pass = $this->validate($post, [
            "app_name|应用名称" => "require",
            "mini_up_version|最低版本" => "require",
        ]);
        if (true !== $pass) {
            return $this->jsonReturn('', 1, $pass);
        }
        $app_id = $post['app_id'];
        $data = Db::table('app')
            ->where('app_id',$app_id)
            ->field('app_version')
            ->find();
        $pd = explode('.',$data['app_version']);
        $pds = explode('.',$post['mini_up_version']);
        if (count($pd)>count($pds)){
            $str = count($pds);
            for ($i=0;$i<$str;$i++){
                if ($pd[$i] < $pds[$i]){
                    return $this->jsonReturn('',1,'最低版本号不能高于正式版本');
                }
                continue;
            }
        }else{
            $str = count($pd);
            for ($i=0;$i<$str;$i++){
                if ($pd[$i] < $pds[$i]){
                    return $this->jsonReturn('',1,'最低版本号不能高于正式版本');
                }
                continue;
            }
        }
        $model = new AppModel();
        $result = $model->appEdit($post);
        return $this->jsonReturn($result);


    }

}