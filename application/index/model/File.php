<?php
/**
 * Created by PhpStorm.
 * User: hitotright
 * Date: 2018/5/31
 * Time: 10:55
 */

namespace app\index\model;

use think\Image;
use think\Log;

class File
{
    private $max_size = 2000000;
    private $allow_ext = 'jpg,png,gif';
    private $local_file;

    private $error='';
    private $public_path ;

    public function image($file){
        $this->local_file = 'uploads' . DS . 'image';
        $this->public_path = ROOT_PATH . 'public' . DS;

        $file = request()->file($file);
        $info = $file->validate(['size' => $this->max_size, 'ext' => $this->allow_ext])
            ->move($this->public_path . $this->local_file);
        if (false === $info) {
            $this->setError($file->getError());
            return false;
        }
        $ext = $info->getExtension();
        $origin_img = $this->local_file . DS . $info->getSaveName();
        $image = Image::open($this->public_path . $origin_img);
//        if ($info->getSize() > 200000) {
//            $small_img = substr($origin_img, 0, strpos($origin_img, $ext) - 1) . "_m." . $ext;
//            $width = $image->width();
//            $height = $image->height();
//            $image->thumb($width / 4, $height / 4)->save($this->public_path . $small_img);
//            $image = Image::open($this->public_path . $origin_img);
//            $image->thumb($width / 2, $height / 2)->save($this->public_path . $origin_img);
//        } else {
            $small_img = substr($origin_img, 0, strpos($origin_img, $ext) - 1) . "_m." . $ext;
            $image->thumb($image->width() / 2, $image->height() / 2)->save($this->public_path . $small_img);
//        }
        return B_URL. DS . $origin_img;
    }

    /**
     * @param $file
     * @return bool
     */
    public function voice($file)
    {
        $this->allow_ext = 'wav,m4a,mp3';
        $this->local_file =  'uploads'.DS.'voice';
        $this->public_path  = ROOT_PATH.'public'.DS;

        $file = request()->file($file);
        $info = $file->validate(['size' => $this->max_size, 'ext' => $this->allow_ext])
            ->move($this->public_path.$this->local_file);
        if (false === $info) {
            $this->setError($file->getError());
            return false;
        }
        $origin_img = $this->local_file.DS.$info->getSaveName();
        $model = new BceModel();
        $bucket=config('bucket');
        $response = $model->pushObjectForFile($bucket,$origin_img);
//        Log::error($response);
        return 'https://'.$bucket.'.bj.bcebos.com'.DS.$origin_img;
    }
    public function word($file)
    {
        $this->allow_ext = 'ppt,doc,docx,xls,xlsx,pdf,pptx';
        $this->local_file =  'uploads'.DS.'word';
        $this->public_path  = ROOT_PATH.'public'.DS;
        $this->max_size = 50000000;
        $file = request()->file($file);

        $info = $file->validate(['size' => $this->max_size, 'ext' => $this->allow_ext])
            ->move($this->public_path.$this->local_file);
        if (false === $info) {
            $error = $file->getError();
            $this->setError( strpos($error,'大小') === false?'文件格式错误，请上传ppt,doc,docx,xls,xlsx,pdf等格式的文件':'文件大小超过50M,请调整');
            return false;
        }
//        return $this->domain.DS.$this->local_file.DS.$info->getSaveName();
        $url= $this->local_file.DS.$info->getSaveName();
        $name=$_FILES["file"]["name"]; //获取上传的文件名称
        $data=[
            'name'=>$name,
            'url'=>B_URL.DS.$url,
        ];
        return $data;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

}