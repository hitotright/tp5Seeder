<?php
/**
 * Created by PhpStorm.
 * User: hitoTright
 * Date: 2018/2/1
 * Time: 14:14
 */

namespace app\index\model;


use think\Db;

class DepartmentModel extends Base
{
    protected $table = 'department';
    private $dp_id, $dp_name, $parent_id, $child_id;

    public function getChild($dp_id)
    {
        return Db::table($this->table)
            ->where('parent_id', '=', (int)$dp_id)
            ->field('dp_id,dp_name,parent_id')->select();
    }

    public function getTree(&$result = [], $dp_id = 0)
    {
        if (empty($result)) {
            $data = $this->getChild($dp_id);
            if (!empty($data)) {
                foreach ($data as $key => $value) {
                    $data[$key] = ['id' => (int)$value['dp_id'], 'label' => $value['dp_name']];
                }
                $this->getTree($data, $dp_id);
            }
            $result = $data;
        } else {
            foreach ($result as $key => $value) {
                $child = $this->getChild($value['id']);
                if (!empty($child)) {
                    foreach ($child as $k => $v) {
                        $child[$k] = ['id' => (int)$v['dp_id'], 'label' => $v['dp_name']];
                    }
                    $this->getTree($child, $dp_id);
                    $result[$key]['children'] = $child;
                }
            }
        }
    }

    public function getAllChild(&$result = [], $dp_id)
    {
        $data = $this->getChild($dp_id);
        if (!empty($data)) {
            foreach ($data as $item) {
                $this->getAllChild($result, $item['dp_id']);
                $result[] = $item['dp_id'];
            }
        }
    }

    public function add()
    {
        $dp_id = Db::table("department")->insertGetId(['dp_name' => $this->dp_name, 'parent_id' => $this->parent_id]);
        return $dp_id > 0 ? $dp_id : 0;
    }

    public function delete()
    {
        Db::table("department")->where('dp_id', 'in', $this->child_id)->delete();
    }

    public function buildDpSelectArr()
    {
        $info = Db::table($this->table)
            ->field('dp_id,dp_name,parent_id')->select();
        //将数组进行排列
        $info = $this->order($info);
        $data = array();
        foreach ($info as $v) {
            $data[$v['dp_id']] = $v['dp_name'];
        }
        $temp = [];
        foreach ($data as $k => $v) {
            $label_raw = str_replace(['&nbsp', '├', ';'], '', $v);
            $temp[] = ['value' => (int)$k, 'label' => $v, 'label_raw' => $label_raw];
        }
        return $temp;
    }

    public function order($array, $pid = 0, $n = 0)
    {
        $arr = array();
        foreach ($array as $v) {
            if ($v['parent_id'] == $pid) {
                $v['dp_name'] = "├" . $v['dp_name'];
                if ($n >= 1) {
                    $v['dp_name'] = str_repeat('&nbsp;', $n * 3) . $v['dp_name'];
                }
                $arr[] = $v;
                $arr = array_merge($arr, $this->order($array, $v['dp_id'], ($n + 1)));
            }
        }
        return $arr;
    }

    /**
     * @return mixed
     */
    public function getDpId()
    {
        return $this->dp_id;
    }

    /**
     * @param mixed $dp_id
     */
    public function setDpId($dp_id)
    {
        $this->dp_id = $dp_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDpName()
    {
        return $this->dp_name;
    }

    /**
     * @param mixed $dp_name
     */
    public function setDpName($dp_name)
    {
        $this->dp_name = $dp_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @param mixed $parent_id
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChildId()
    {
        return $this->child_id;
    }

    /**
     * @param mixed $child_id
     */
    public function setChildId($child_id)
    {
        $this->child_id = $child_id;
        return $this;
    }

}