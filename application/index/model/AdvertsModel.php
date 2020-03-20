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
use think\Session;

class AdvertsModel extends Base
{
    protected $searchColumn = [
        'ad_type' => ['ad_type','='],
        'ad_name' => ['ad_title','like'],
        'ad_local' => ['ad_position','='],
        'ad_img' => ['ad_image','like'],
        'ad_url' => ['ad_url','like'],
    ];

    public function getList($param){
//        $bid = session('org_b_id');
        $query = Db::table('adverts')
            ->where('add_user_id',1)
            ->where('ad_status',1)
            ->field('ad_id,ad_url,ad_type,ad_title,ad_position,ad_image,add_time')
            ->order('ad_id','desc');
        $this->checkSearch($query, $param['search']);
        $data = $this->autoPaginate($query, $param['paginate'])->toArray();
        Log::error($data);
        return $data;
    }

    public function delAdver($param){
        $id = $param['id'];
        return Db::table('adverts')
            ->where('ad_id',$id)
            ->update(['ad_status'=>2]);
    }

    public function adinsert($post){
//        $bid = session('org_b_id');
        $bid = 1;
        $data['ad_type'] = $post['ad_type'];
        $data['ad_title'] = $post['ad_title'];
        $data['ad_url'] = $post['ad_url'];
        $data['ad_image'] = $post['ad_image'];
        $data['ad_position'] = $post['ad_local'];
        $data['ad_status'] = 1;
        $data['add_time'] = date('Y-m-d H:i:s');
        $data['add_user_id'] = $bid;
        return Db::table("adverts")->insert($data);
    }

    public function update($post){
        $ad_id = $post['ad_id'];
        $title = $post['ad_title'];
        $ad_url = $post['ad_url'];
        $ad_image = $post['ad_image'];
        $ad_position = $post['ad_position'];
        $add_time = date('Y-m-d H:i:s');
        $ad_type = $post['ad_type'];
        return Db::table('adverts')
            ->where('ad_id',$ad_id)
            ->update(['ad_title'=>$title,'ad_position'=>$ad_position,'ad_type'=>$ad_type,'ad_image'=>$ad_image,'ad_url'=>$ad_url,'add_time' => $add_time]);


    }

}