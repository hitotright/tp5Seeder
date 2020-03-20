<?php
/**
 * Created by PhpStorm.
 * User: hitoTright
 * Date: 2018/2/1
 * Time: 14:01
 * Info: 分类列表;添加分类;删除分类
 */

namespace app\index\controller\website;

use app\index\controller\Base;
use app\index\model\AdvertsModel;
use think\Log;
use think\Db;

class Adverts extends Base
{
    public function index()
    {
        $model = new AdvertsModel();
        $result = $model->getList($this->request->param());
        return $this->jsonReturn($result);
    }

//    删除广告
    public function delete()
    {
        $model = new AdvertsModel();
        $result = $model->delAdver($this->request->param());
        return $this->jsonReturn($result);
    }

    public function add()
    {
        $post = $this->request->param();
        $pass = $this->validate($post, [
            "ad_title|广告名称" => "require",
            "ad_local|广告位置" => "require",
            "ad_type|客户端" => "require",
        ]);
        if (true !== $pass) {
            return $this->jsonReturn('', 1, $pass);
        }
        $model = new AdvertsModel();
        $result = $model->adinsert($post);
        return $this->jsonReturn($result);
    }

    public function edit()
    {
        $post = $this->request->param();
         $pass = $this->validate($post, [
            "ad_title|广告名称" => "require",
            "ad_position|广告位置" => "require",
            "ad_type|客户端" => "require",
        ]);
        if (true !== $pass) {
            return $this->jsonReturn('', 1, $pass);
        }
        $model = new AdvertsModel();
        $result = $model->update($post);
        return $this->jsonReturn($result);
    }




}