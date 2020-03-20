<?php
/**
 * Created by PhpStorm.
 * User: hitotright
 * Date: 2018/8/2
 * Time: 9:49
 */

namespace app\index\controller\admin;


use app\index\controller\Base;
use app\index\model\ActionModel;
use app\index\model\DepartmentModel;
use app\index\model\PermissionModel;
use app\index\model\PositionModel;

class Permission extends Base
{
    public function index(){
        $position = new PositionModel();
        $permission = new PermissionModel();
        $action = new ActionModel();
        $dpData = [];
        return json([
            'psData'=>$position->getAllPosition(),
            'pmData'=>$permission->getPermissionTree(),
            'actionData'=>$action->getAll(),
            'dpData'=>$dpData,
            ]);
    }

    public function indexPosition(){
        $id = (int) $this->request->param('id',0);
        $permission = new PermissionModel();
        return json($permission->getPsInfoArrById($id));
    }

    public function editPermission(){
        $post = $this->request->post();
        $pass = $this->validate($post, [
            'pmId|菜单选择' => 'require|integer',
            'psId|职位选择' => 'require|array',
            'psInfo|权限选择' => 'require|array'
        ]);
        if (true !== $pass) {
            return $this->jsonReturn('', 1, $pass);
        }

        $ps_id = array_column($post['psId'],'ps_id');
        foreach ($post['psInfo'] as $key=>$item){
            if(!in_array($item['ps_id'],$ps_id)){
                unset($post['psInfo'][$key]);
            }
        }
        $model = new PermissionModel();
        $model->deleteSurplusPosition($post['pmId'],$ps_id);
        if($model->permissionChange($post['pmId'],$post['psInfo'])){
            return $this->jsonReturn($post);
        }else{
            return $this->jsonReturn('',1,$model->getMessage());
        }
    }
}