<?php
/**
 * Created by PhpStorm.
 * User: hitoTright
 * Date: 2017/8/13
 * Time: 20:50
 */

namespace app\api\model;

use think\Model;

class Base extends Model
{
    public function paginate($listRows = null, $simple = false, $config = [])
    {
        if (input('?param.limit')) {
            $listRows = input('param.limit');
        }
        if (input('?param.total')) {
            $simple = (int)input('param.total');
        }
        if (input('?param.page')){
        $config['page'] = input('param.page');
        }
        return parent::paginate($listRows, $simple, $config);
    }
}