<?php

use think\Log;

if (!function_exists('array_column')) {
    function array_column(array $array, $column, $index_key = null)
    {
        $data = [];
        foreach ($array as $v) {
            $vv = $column !== null ? $v[$column] : $v;
            if ($index_key === null) {
                $data[] = $vv;
            } else {
                $data[$v[$index_key]] = $vv;
            }
        }
        return $data;
    }
}

//参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：是否为HTTPS，参数4：提交的$cookies,参数5：是否返回$cookies
function curl_request($url, $post = '', $https = false, $cookie = '', $returnCookie = 0)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
    // https请求 不验证证书和hosts
    if ($https) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    if ($post) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    if ($cookie) {
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }
    curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
    curl_setopt($curl, CURLOPT_TIMEOUT, 2);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {
        return curl_error($curl);
    }
    curl_close($curl);
    if ($returnCookie) {
        list($header, $body) = explode("\r\n\r\n", $data, 2);
        preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
        $info['cookie'] = substr($matches[1][0], 1);
        $info['content'] = $body;
        return $info;
    } else {
        return $data;
    }
}

function errorLog(Exception $e){
    Log::error($e->getMessage().PHP_EOL.$e->getTraceAsString());
}

if (!function_exists('mb_str_split')) {
    function mb_str_split($str,$len){
        $arr= preg_split('/(?<!^)(?!$)/u', $str );
        $res = array_chunk($arr,$len);
        foreach ($res as $k=>$v){
            $res[$k]=implode('',$v);
        }
        return $res;
    }
}

