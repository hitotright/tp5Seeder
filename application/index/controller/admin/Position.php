<?php
/**
 * Created by PhpStorm.
 * User: $zhuxingcheng
 * Date: 2018/7/31
 * Time: 10:05
 */

namespace app\index\controller\admin;


use app\index\controller\Base;
use app\index\model\PositionModel;
use think\Db;
use think\Log;

class Position extends Base
{


    public function index(){
        $model = new PositionModel();
        $result = $model->getList($this->request->param());
        return $this->jsonReturn($result);
    }

    public function add(){
        $post = $this->request->post();
        $pass= $this->validate($post,[
            'ps_name|职位名称'=>'require|min:2|max:30',
        ]);
        if(true !== $pass){
            return $this->jsonReturn('',1,$pass);
        }
        $model = new PositionModel();
        if(!$model->checkUnique($post['ps_name'])){
            return $this->jsonReturn('',1,'职位名称已存在');
        }
        $model->setPsName($post['ps_name']);
        $model->add();
        return $this->jsonReturn($model->getPsId());
    }

    public function edit(){
        $post = $this->request->post();
        $pass= $this->validate($post,[
            'ps_id|职位编号'=>'require|number',
            'ps_name|职位名称'=>'require|min:2|max:30',
        ]);
        if(true !== $pass){
            return $this->jsonReturn('',1,$pass);
        }
        $model = new PositionModel();
        if(!$model->checkUnique($post['ps_name'],$post['ps_id'])){
            return $this->jsonReturn('',1,'职位名称已存在');
        }
        Db::table('position')->update(['ps_id'=>$post['ps_id'],'ps_name'=>trim($post['ps_name'])]);
        return $this->jsonReturn('');
    }

    public function delete(){
        $post = $this->request->post();
        $pass= $this->validate($post,[
            'ps_id|职位编号'=>'require|number',
        ]);
        if(true !== $pass){
            return $this->jsonReturn('',1,$pass);
        }
        $model = new PositionModel();
        $model->deleteById($post['ps_id']);
        return $this->jsonReturn('');
    }

}