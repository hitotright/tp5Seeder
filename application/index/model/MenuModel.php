<?php
/**
 * Created by PhpStorm.
 * User: hitotright
 * Date: 2018/7/27
 * Time: 9:33
 */

namespace app\index\model;

use think\Db;

class MenuModel extends Base
{
    public function getTopMenu(){
        return Db::table('menu')->where('disabled',0)->where('parent_id',0)
            ->order('display_order','asc')
            ->field("menu_id,name,tab_id,icon,icon_color")
            ->select();
    }

    public function getChildMenu($parent_id,$permission_id_arr=[]){
        $query= Db::table('menu')->where('disabled',0)->where('parent_id',$parent_id)
            ->order('display_order','asc')
            ->field('name,tab_id,icon,url');
        if(!empty($permission_id_arr)&&session('org_user_id') != SUPER_USER_ID){
            $query->where('permission_id','in',$permission_id_arr);
        }
        return $query->select();
    }


}