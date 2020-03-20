<?php
/**
 * Created by PhpStorm.
 * User: hitotright
 * Date: 2018/7/27
 * Time: 15:31
 */

namespace app\index\model;


use app\common\Utility;
use think\Db;
use think\Exception;
use think\Log;
use think\Request;
use think\Session;

class PermissionModel
{

    private $message='';

    /**
     * 权限认证
     */
    public function checkAuthorize(Request $request)
    {
        //通用流程判断权限
        $ps_id = Session::get('org_ps_id');
        $user_id = Session::get('org_user_id');
        if(SUPER_USER_ID == $user_id){
            return true;
        }
        // 节点列表路由权限
        $controller = $request->route('controller');
        $dir = $request->route('dir');
        if($dir !== null){
            $controller = $dir.'/'.$controller;
        }
        $controller = strtolower(str_replace('.','/',$controller));
        $permission_id = Db::table('permission')->where('controller','=',$controller)->value('permission_id',0);
        if($permission_id <=0){
            //不受限制的控制器
            return true;
        }
        $action = $request->route('action');
        $action = preg_split('/[A-Z]/',$action);
        $action = preg_split('/_/',$action[0]);
        $action = $action[0];
        $action_id = Db::table('action')->where('action','=',$action)->value('action_id',0);
        if($action_id == 0){
            //不受限制的动作
            return true;
        }
        $action_arr = Db::table('permission_position pp')
            ->join('permission_position_action ppa','pp.pp_id = ppa.pp_id','left')
            ->where('pp.permission_id','=',$permission_id)
            ->where('pp.ps_id','=',$ps_id)
            ->column('ppa.action_id');
        if(in_array($action_id,$action_arr)){
            return true;
        }
        return false;
    }

    public function getAllowPermissionId(){
        $controller_arr = Db::table('permission p')
            ->join('permission_position pp','p.permission_id = pp.permission_id','left')
            ->where('pp.ps_id','=',session('org_ps_id'))->group('p.permission_id')->column('p.permission_id');
         return $controller_arr;
    }

    public function getPsInfoArrById($permission_id){
        $ps_arr= Db::table('permission_position')
            ->where('permission_id','=',$permission_id)->column('ps_id,pp_id,range_type');
        $result = [];
        if(!empty($ps_arr)){
            foreach ($ps_arr as $item){
                $result[$item['pp_id']] = $item;
                $result[$item['pp_id']]['action'] =[];
                $result[$item['pp_id']]['range'] =[];
            }
            $pp_id = array_column($ps_arr,'pp_id');
            $ppa_arr = Db::table('permission_position_action')->where('pp_id','in',$pp_id)->select();
            if(!empty($ppa_arr)){
                foreach ($ppa_arr as $ppa){
                    $result[$ppa['pp_id']]['action'][]=$ppa['action_id'];
                }
            }
            $ppd_arr = Db::table('permission_position_range')->where('pp_id','in',$pp_id)->select();
            if(!empty($ppd_arr)){
                foreach ($ppd_arr as $ppd){
                    $result[$ppd['pp_id']]['range'][]=$ppd['dp_id'];
                }
            }
            $tmp = [];
            foreach ($result as $item){
                $tmp[$item['ps_id']] = $item;
            }
            $result = $tmp;
        }
        return $result;
    }

    public function getPermissionTree(){
        $parent= Db::table('permission')->field('permission_id,parent_id,name')
            ->where('parent_id','=',0)->select();
        $result = [];
        if(!empty($parent)){
            foreach ($parent as $item){
                $result[$item['permission_id']]=[
                    'id'=>$item['permission_id'],
                    'label'=>$item['name']
                ];
            }
        }
        $child = Db::table('permission')->field('permission_id,parent_id,name')
            ->where('parent_id','<>',0)->select();
        if(!empty($child)){
            foreach ($child as $item){
                if(!isset($result[$item['parent_id']]['children'])){
                    $result[$item['parent_id']]['children'] = [];
                }
                $result[$item['parent_id']]['children'][] = [
                    'id'=>$item['permission_id'],
                    'label'=>$item['name']
                ];
            }
        }
        return array_values($result);
    }

    public function permissionChange($permission_id,$ps_action_range_arr){
        foreach ($ps_action_range_arr as $arr){
            $range_type = 2;
            if(in_array(-1,$arr['range'])){
                $arr['range']=[-1];
                $range_type = 3;
            }
            if(in_array(0,$arr['range'])){
                $arr['range']=[0];
                $range_type = 1;
            }
            if(isset($arr['pp_id'])){
                $pp_id = $arr['pp_id'];
            }else{
                $pp_id = Db::table('permission_position')->where('permission_id','=',$permission_id)
                    ->where('ps_id','=',$arr['ps_id'])->value('pp_id',0);
            }
            if($pp_id <=0){
                $pp_id = Db::table('permission_position')->insertGetId(['permission_id'=>$permission_id,
                    'ps_id'=>$arr['ps_id'],'range_type'=>$range_type]);
            }else{
                try{
                    Db::table('permission_position')->where('pp_id','=',$pp_id)->update(['range_type'=>$range_type]);
                }catch (Exception $exception){
                    Log::error($exception->getMessage().PHP_EOL.$exception->getTraceAsString());
                    $this->message='错误编号，请刷新';
                    return false;
                }
            }
            try{
                Log::error($arr);
                Db::startTrans();
                Db::table('permission_position_action')->where('pp_id','=',$pp_id)->delete();
                Db::table('permission_position_range')->where('pp_id','=',$pp_id)->delete();
                if(!empty($arr['range'])){
                    $arr_range = [];
                    foreach ($arr['range'] as $dp_id){
                        $arr_range[] = ['pp_id'=>$pp_id,'dp_id'=>$dp_id];
                    }
                    Db::table('permission_position_range')->insertAll($arr_range);
                }
                if(!empty($arr['action'])){
                    $arr_action = [];
                    foreach ($arr['action'] as $action_id){
                        $arr_action[] = ['pp_id'=>$pp_id,'action_id'=>$action_id];
                    }
                    Db::table('permission_position_action')->insertAll($arr_action);
                }
                Db::commit();
            }catch (Exception $exception){
                Db::rollback();
                Log::error($exception->getMessage().PHP_EOL.$exception->getTraceAsString());
                $this->message='保存错误，请刷新';
                return false;
            }
        }
        return true;
    }

    public function deleteSurplusPosition($permission_id,$ps_arr){
        $ps_in = Db::table('permission_position')->where('permission_id','=',$permission_id)
            ->column('ps_id');
        $ps_not_in = [];
        if(!empty($ps_in)){
            foreach ($ps_in as $ps_id){
                if(!in_array($ps_id,$ps_arr)){
                    $ps_not_in[]=$ps_id;
                }
            }
        }
        if(!empty($ps_not_in)){
            Db::table('permission_position')->where('permission_id','=',$permission_id)
                ->where('ps_id','in',$ps_not_in)->delete();
        }
    }

    public function getMessage(){
        return $this->message;
    }

}