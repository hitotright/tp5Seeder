<?php
/**
 * Created by PhpStorm.
 * User: hitoTright
 * Date: 2018/2/1
 * Time: 14:01
 * Info: 部门列表;添加部门;删除部门
 */

namespace app\index\controller\admin;

use app\index\controller\Base;
use app\index\model\DepartmentModel;
use think\Log;
use think\Db;

class Department extends Base
{
    public function index()
    {
        $model = new DepartmentModel();
        $result = [];
        $model->getTree($result);
        return json($result);
    }

    public function add()
    {
        $post = $this->request->post();
        $pass = $this->validate($post, [
            'id|部门编号' => 'require|number|min:1',
            'name|部门名称' => 'require|max:100',
        ]);
        if (true !== $pass) {
            return $this->jsonReturn('', 1, $pass);
        }
        $model = new DepartmentModel();
        $model->setDpName($post['name']);
        $model->setParentId($post['id']);
        $model->add();
        return $this->jsonReturn(['id' => $model->getDpId(), 'label' => $model->getDpName()], '', '添加成功');
    }

    public function delete()
    {
        $id = $this->request->get('id', 0);
        if ($id <= 0) {
            return $this->jsonReturn('', 1, '错误编号,请刷新');
        }
        $model = new DepartmentModel();
        $child = [$id];
        $model->getAllChild($child, $id);
        $model->setChildId($child);
        $model->delete();
        return $this->jsonReturn('', '', '删除成功');
    }

    public function index_select()
    {
        $model = new DepartmentModel();
        $result = $model->buildDpSelectArr();
        return json($result);
    }
    public function addOne()
    {
        $post = $this->request->post();
        $post['name'] = trim($post['name']);
        $pass = $this->validate($post, [
            'name|部门名称' => 'require|max:100',
        ]);
        if (true !== $pass) {
            return $this->jsonReturn('', 1, $pass);
        }
        Db::table('department')->insert(['dp_name'=>$post['name'],'parent_id'=>0]);
        return $this->jsonReturn([], '', '添加成功');
    }
}