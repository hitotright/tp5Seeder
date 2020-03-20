<?php
/**
 * Created by PhpStorm.
 * User: hitoTright
 * Date: 2018/3/13
 * Time: 10:55
 * Info: 公用方法
 */
namespace app\index\controller\common;

use app\common\Utility;
use app\index\controller\Base;
use app\index\model\ProfessionModel;
use think\Log;

class Dialog extends Base
{
    protected function checkLogin(){
        $origin = $this->request->header('Origin');
        if(strpos($origin,'houxue.com') !== false){
            header("Access-Control-Allow-Origin:*");
        }else{
            header("Access-Control-Allow-Origin:https://manage.houxue.com");
        }
        header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE'); //支持的http动作
        header('Access-Control-Allow-Headers:origin,x-requested-with,content-type,X_Requested_With');  //响应头
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
            echo $origin; exit;
        }
    }

    public function uediterServer(){
        date_default_timezone_set("Asia/Chongqing");
        error_reporting(E_ERROR);
        header("Content-Type: text/html; charset=utf-8");

        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(EXTEND_PATH."uediter".DS."config.json")), true);
        $action = $_GET['action'];
        switch ($action) {
            case 'config':
                $result =  json_encode($CONFIG);
                break;
            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $result = include(EXTEND_PATH."uediter".DS."action_upload.php");
                break;
            /* 列出图片 */
            case 'listimage':
                $result = include(EXTEND_PATH."uediter".DS."action_list.php");
                break;
            /* 列出文件 */
            case 'listfile':
                $result = include(EXTEND_PATH."uediter".DS."action_list.php");
                break;
            /* 抓取远程文件 */
            case 'catchimage':
                $result = include(EXTEND_PATH."uediter".DS."action_crawler.php");
                break;
            default:
                $result = json_encode(array(
                    'state'=> '请求地址出错'
                ));
                break;
        }
        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }
}