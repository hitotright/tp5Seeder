<?php
/**
 * Created by PhpStorm.
 * User: $zhuxingcheng
 * Date: 2018/8/3
 * Time: 15:19
 */

namespace app\index\model;


use think\Db;

class GlobalSetModel extends Base
{
    public static function getValue($key){
        return Db::table('global_set')->where('gs_option', '=', $key)->value('gs_value');
    }
}