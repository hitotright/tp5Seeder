<?php
/**
 * 基本登入登出以及功能列表
 */
namespace app\index\controller\admin;

use app\index\controller\Base;
use app\index\model\MenuModel;
use app\index\model\PermissionModel;
use think\Log;
use think\Session;
use app\common\Utility;
use think\Db;

class Home extends Base
{

    public function menu()
    {
        $menu_info =  [];
        $permissionModel = new PermissionModel();
        $permission_id_arr = $permissionModel->getAllowPermissionId();
        $MenuModel = new MenuModel();
        $menu_top = $MenuModel->getTopMenu();
        if (!empty($menu_top)) {
            foreach ($menu_top as $k => $v) {
                $child_menu= $MenuModel->getChildMenu($v['menu_id'],$permission_id_arr);
                if(!empty($child_menu)){
                    $menu_top[$k]['subs']=$child_menu;
                    $menu_info[]=$menu_top[$k];
                }
            }
        }
        return json($menu_info);
    }

    
}
