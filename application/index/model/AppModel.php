<?php
/**
 * Created by PhpStorm.
 * User: hitotright
 * Date: 2019/6/24
 * Time: 15:09
 */

namespace app\index\model;


use app\common\Utility;
use think\Db;
use think\Exception;
use think\Log;
use think\Request;
use think\Session;

class AppModel extends Base
{
    protected $searchColumn = [
        'app_name' => ['app_name','like'],
    ];

    public function getList($param){
        $query = Db::table('app')
            ->field('app_id,app_name,app_url,test_version,app_version,mini_up_version')
            ->order('app_id','desc');
        $this->checkSearch($query, $param['search']);
        $data = $this->autoPaginate($query, $param['paginate'])->toArray();
        Log::error($data);
        return $data;
    }

    public function delApp($post){
        $id = $post['id'];
        return Db::table('app')
            ->where('app_id',$id)
            ->delete();
    }

    public function appInsert($post){
        $request = Request::instance();
//        $bid = session('org_b_id');
        $bid = 1;
        $data['app_name'] = $post['app_name'];
        $data['test_version'] = $post['test_version'];
        $data['mini_up_version'] = $post['test_version'];
        $data['test_url'] = $post['test_url'];
        $data['sign_secret'] = md5($post['sign_secret']);
        $res = Db::table('app')->insert($data);
        if ($res){
            $id = Db::table('app')->getLastInsID();
            $str['version_url'] = $data['test_url'];
            $str['app_id'] = $id;
            $str['version'] = $data['test_version'];
            $str['add_user_id'] = $bid;
            $str['add_time'] = date('Y-m-d H:i:s');
            $str['upload_ip'] = $request->ip();
            $str['memo'] = '新添加应用';
            return Db::table('app_version')->insert($str);
        }else{
            return $res;
        }
    }

    public function appUpload($post){
        $request = Request::instance();
//        $bid = session('org_b_id');
        $bid = 1;
        $test_version = $post['test_version'];
        $test_url = $post['test_url'];
        $app_id = $post['app_id'];
        $res = Db::table('app')
            ->where('app_id',$app_id)
            ->update(['test_version'=>$test_version,'test_url'=>$test_url]);
        if ($res){
            $str['version_url'] = $test_url;
            $str['app_id'] = $app_id;
            $str['version'] = $test_version;
            $str['add_user_id'] = $bid;
            $str['add_time'] = date('Y-m-d H:i:s');
            $str['upload_ip'] = $request->ip();
            $str['memo'] = $post['memo'];
            return Db::table('app_version')->insert($str);
        }else{
            return $res;
        }
    }

    public function appEdit($post){
        $app_name = $post['app_name'];
        $app_id = $post['app_id'];
        $mini = $post['mini_up_version'];
        return Db::table('app')
            ->where('app_id',$app_id)
            ->update(['app_name'=>$app_name,'mini_up_version'=>$mini]);
    }


}