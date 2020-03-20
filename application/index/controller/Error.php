<?php
/**
 * Created by PhpStorm.
 * User: hitotright
 * Date: 2018/5/17
 * Time: 10:09
 */

namespace app\index\controller;

use app\index\model\CategoryModel;
use think\Controller;

class Error extends Controller
{
    public function index()
    {
        header("HTTP/1.0 404 Not Found");
        return response('404',404);
    }
}