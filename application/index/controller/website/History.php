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
use app\index\model\AppVersionModel;
use think\Log;
use think\Db;

class History extends Base
{
    public function index(){
        $model = new AppVersionModel();
        Log::error($this->request->param());
        $result = $model->getList($this->request->param());
        return $this->jsonReturn($result);
    }

    public function getAppForSel(){
        $model = new AppVersionModel();
        $result = $model->getApp();
        return $this->jsonReturn($result);
    }

    public function add(){
        $post = $this->request->param();
        $pass = $this->validate($post, [
            "app_id|应用名称" => "require",
            "test_version|应用版本号" => "require",
            "test_url|下载地址" => "require",
            "memo|更新说明" => "require",
        ]);
        if (true !== $pass) {
            return $this->jsonReturn('', 1, $pass);
        }
        $model = new AppVersionModel();
        $result = $model->appInsert($post);
        return $this->jsonReturn($result);
    }

    public function getHistoryForEdit(){
        $post = $this->request->post();
        $pass = $this->validate($post, [
            'v_id' => 'require',
        ]);
        if (true !== $pass) {
            return $this->jsonReturn('', 1, $pass);
        }
        $v_id = $post['v_id'];
        $str = Db::table('app_version')
            ->where('v_id',$v_id)
            ->field('v_id,app_id,memo,version,version_url')
            ->find();
        $app_id = $str['app_id'];
        $data = Db::table('app')
            ->where('app_id',$app_id)
            ->field('app_id,app_name')
            ->find();
        $data['memo'] = $str['memo'];
        $data['v_id'] = $str['v_id'];
        $data['app_id'] = $str['app_id'];
        $data['version'] = $str['version'];
        $data['version_url'] = $str['version_url'];
        return $this->jsonReturn($data);
    }

    public function edit(){
        $post = $this->request->param();
        $app_id = $post['app_id'];
        $data = Db::table('app')
            ->where('app_id',$app_id)
            ->field('app_version')
            ->find();
        $pd = explode('.',$data['app_version']);
        $pds = explode('.',$post['version']);
        if (count($pd)>count($pds)){
            $str = count($pds);
            for ($i=0;$i<$str;$i++){
                if ($pd[$i] > $pds[$i]){
                    return $this->jsonReturn('',1,'当前版本低于正式版本');
                }
                continue;
            }
        }else{
            $str = count($pd);
            for ($i=0;$i<$str;$i++){
                if ($pd[$i] > $pds[$i]){
                    return $this->jsonReturn('',1,'当前版本低于正式版本');
                }
                continue;
            }
        }
        $model = new AppVersionModel();
        $result = $model->edit($post);
        return $this->jsonReturn($result);
    }

}