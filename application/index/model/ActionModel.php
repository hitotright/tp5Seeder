<?php
/**
 * Created by PhpStorm.
 * User: hitotright
 * Date: 2018/8/2
 * Time: 15:25
 */

namespace app\index\model;


use think\Db;

class ActionModel extends Base
{
    public function getAll(){
        return Db::table('action')->select();
    }
}