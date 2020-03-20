<?php
/**
 * Created by PhpStorm.
 * User: $zhuxingcheng
 * Date: 2018/7/31
 * Time: 10:04
 */

namespace app\index\model;


use think\Db;

class PositionModel extends Base
{
    private $ps_id,$ps_name;
    protected $searchColumn = [
        'ps_id'=>['ps_id','like'],
        'ps_name'=>['ps_name','like']
    ];

    public function getList($param){
        $query = Db::table('position');
        $this->checkSearch($query,$param['search']);
        $data= $this->autoPaginate($query,$param['paginate'])->toArray();
        return $data;
    }

    public function checkUnique($ps_name,$expire_id=0){
        $count = Db::table("position")
            ->where('ps_name','=',$ps_name)->where('ps_id','<>',$expire_id)->count();
        return $count >0?false:true;
    }

    public function add(){
        $ps_id = Db::table("position")->insertGetId(['ps_name'=>$this->ps_name]);
        $this->ps_id = $ps_id;
        return $ps_id > 0 ? $ps_id : 0;
    }

    public function deleteById($ps_id){
        return Db::table('position')
            ->where('ps_id','=',$ps_id)->delete();
    }

    public function getAllPosition($ps_id_arr=[]){
        $query= Db::table('position');
        if($ps_id_arr !=[]){
            $query->where('ps_id','in',$ps_id_arr);
        }
        return $query->select();
    }

    public function setPsName($ps_name)
    {
        $this->ps_name = $ps_name;
        return $this;
    }

    public function getPsName()
    {
        return $this->ps_name;
    }

    /**
     * @return mixed
     */
    public function getPsId()
    {
        return $this->ps_id;
    }
}