<?php
/**
 * Created by PhpStorm.
 * User: hitoTright
 * Date: 2017/12/26
 * Time: 10:29
 */

namespace app\index\model;


use think\Exception;
use think\db\Query;
use think\Request;
use think\Log;
use think\Db;

class Base
{
    private $message='';
    protected $searchColumn;

    /**
     * 自动分页
     * @param Query $Query
     * @param array $paginate
     * @param null $listRows
     * @param bool $simple
     * @param array $config
     * @param string $prop_p
     * @param string $order_p
     * @return \think\Paginator
     */
    public function autoPaginate(Query &$Query,$post,$listRows = null, $simple = false, $config = [], $prop_p = '', $order_p = '')
    {
        if(!empty($post)&&$listRows == null){
            $listRows = isset($post['pageSize'])?$post['pageSize']:50;
        }
//        if(!empty($post)&&$simple == false){
//            $simple = isset($post['total'])&&$post['total']>0?$post['total']:null;
//        }
        if(!empty($post)&&empty($config)){
            $config['page']=isset($post['currentPage'])?$post['currentPage']:1;
        }
        if (empty($prop_p) && empty($order_p)) {
            if(!empty($post)&&isset($post['prop'])){
                $column=$post['prop'];
                $order=$post['order'] == 'descending'?'desc':'asc';
                return $Query->order($column,$order)->paginate($listRows, $simple, $config);
            }else{
                return $Query->paginate($listRows, $simple, $config);
            }
        } else {
            return $Query->order($prop_p,$order_p)->paginate($listRows, $simple, $config);
        }
    }

    /**
     * 添加权限范围
     * @param Query $query
     * @param $b_column
     * @param $dp_column
     * @param $user_column
     * @param string $sc_column 支持学校范围必填
     * @return bool
     */
    protected function checkRange(Query &$query,$b_column,$dp_column,$user_column,$sc_column=''){
        $user_id = session('org_user_id');
        $ps_id = session('org_ps_id');
        $query->where($b_column,'=',session('org_b_id'));
        if(session('org_user_type') == 2){
            //学校类型
            $query->where($sc_column,'=',session('org_user_school'));
            return true;
        }
        $ps_id_arr = explode(',',$ps_id);
        if($user_id != SUPER_USER_ID){
            $controller = request()->route('controller');
            $dir = request()->route('dir');
            if($dir !== null){
                $controller = $dir.'/'.$controller;
            }
            $controller = str_replace('.','/',$controller);
            $permission_id = Db::table('permission')->where('controller','=',$controller)->value('permission_id',0);
            if($permission_id <=0){
                //不受限制的控制器
                return true;
            }
            $result = Db::table('permission_position pp')
                ->join('permission_position_range ppr','pp.pp_id = ppr.pp_id','left')
                ->where('pp.permission_id','=',$permission_id)
                ->where('pp.ps_id','in',$ps_id_arr)
                ->field('pp.pp_id,pp.range_type,ppr.dp_id')->select();
            if(empty($result)){
                return true;
            }
            $pp_arr=$pr_arr=[];
            foreach ($result as $value){
                if($value['dp_id']>0){
                    $pp_arr[$value['pp_id']][]=$value['dp_id'];
                }
                $pr_arr[$value['range_type']][]=$value['pp_id'];
            }
            if(in_array(1,array_keys($pr_arr))){
                return true;
            }elseif(in_array(2,array_keys($pr_arr))){
                $dp_arr = [];
                $pr_arr[2]=array_unique($pr_arr[2]);
                foreach ($pr_arr[2] as $pp_id){
                    $dp_arr = array_merge($dp_arr,$pp_arr[$pp_id]);
                }
                $query->where($dp_column,'in',$dp_arr);
            }else{
                $query->where($user_column,'=',$user_id);
            }
        }
    }

    /**
     * @param Query $query
     * @param array $search
     * @param array $searchColumn
     */
    protected function checkSearch(Query &$query,$search,$searchColumn=[]){
        if(!empty($searchColumn)){
            $this->searchColumn = $searchColumn;
        }
        if(!empty($this->searchColumn)){
            foreach( $this->searchColumn as $key=>$where)
            {
                $column = $where[0];
                $op = $where[1];
                $value = isset($search[$key])?$search[$key]:'';
                if ($value === 0 OR $value === "0") {
                    $istrue = $value === ""?false:true;
                } else {
                    $istrue = empty($value)?false:true;
                }
                if(!empty($value)&&strtolower($op) == 'like'){
                    $value=" like '%".$value."%'";
                    $op='exp';
                }
                if (isset($where[2]) && $istrue) {
                    if ($where[2] == "time") {
                        $value[0] = $value[0]." 00:00:00";
                        $value[1] = $value[1]." 23:59:59";
                    } elseif($where[2] == "one_time"){
                        $time = $value;
                        $time_arr[0] = $time." 00:00:00";
                        $time_arr[1] = $time." 23:59:59";
                        $value = $time_arr;
                    }
                }
                if(!empty($value)&&strtolower($op) == 'between'){
                    $value=" between '".$value[0]."' and '".$value[1]."'";
                    $op='exp';
                }
                if(!empty($istrue)){
                    $query->where($column,$op,$value);
                }
            }
        }
    }

    /**
     * 错误日志
     * @param Exception $e
     * @return $this
     */
    public function errorLog(Exception $e){
        Log::error($e->getMessage().PHP_EOL.$e->getTraceAsString());
        return $this;
    }

   public function setMessage($msg){
        $this->message = $msg;
        return $this;
   }

    /**
     * @return $message
     */
    public function getMessage()
    {
        return $this->message;
    }

}