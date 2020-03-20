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

class AppVersionModel extends Base
{
    protected $searchColumn = [
        'app_name' => ['b.app_name','like'],
        'status' => ['a.status','='],
    ];

    public function getList($param){
        $query = Db::table('app_version a')
            ->join('app b','a.app_id = b.app_id','left')
            ->field('a.v_id,b.app_name,a.version,a.version_url,a.add_time,a.add_user_id,a.upload_ip,a.status,a.check_ip,a.check_user_id,a.check_time,a.memo')
            ->order('a.v_id','desc');
        $this->checkSearch($query, $param['search']);
        $data = $this->autoPaginate($query, $param['paginate'])->toArray();
        foreach ($data['data'] as $k => $v){
            $id = $v['add_user_id'];
            $ids = $v['check_user_id'];
            $sc = Db::table("user")
                ->where('user_id',$id)
                ->field('user_name')
                ->find();
            $sh = Db::table("user")
                ->where('user_id',$ids)
                ->field('user_name')
                ->find();
            $data['data'][$k]['upload_name'] = $sc['user_name'];
            $data['data'][$k]['check_name'] = $sh['user_name'];
        }
        return $data;
    }

    public function getApp(){
        return Db::table('app')
            ->field('app_id,app_name')
            ->select();
    }

    public function appInsert($post){
        $request = Request::instance();
//        $bid = session('org_b_id');
        $bid = 1;
        $app_id = $post['app_id'];
        $test_version = $post['test_version'];
        $test_url = $post['test_url'];
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

    public function edit($post){
        $request = Request::instance();
//        $bid = session('org_b_id');
        $bid = 1;
        $ip = $request->ip();
        $v_id = $post['v_id'];
        $time = date('Y-m-d H:i:s');
        $type = $post['type'];
        if ($post['type'] == 3){
            return Db::table('app_version')
                ->where('v_id',$v_id)
                ->update(['check_user_id'=>$bid,'check_ip'=>$ip,'check_time'=>$time,'status'=>$type]);
        }else{
            $app_id = $post['app_id'];
            $app_version = $post['version'];
            $app_url = $post['version_url'];
            Db::table('app_version')
                ->where('v_id',$v_id)
                ->update(['check_user_id'=>$bid,'check_ip'=>$ip,'check_time'=>$time,'status'=>$type]);
            Db::table('app')
                ->where('app_id',$app_id)
                ->update(['app_version'=>$app_version,'app_url'=>$app_url]);
            return true;
        }
    }




}