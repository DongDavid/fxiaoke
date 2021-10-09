<?php
namespace Dongdavid\Fxiaoke;

use Dongdavid\Fxiaoke\utils\Http;
use Dongdavid\Fxiaoke\utils\Redis;

class Authentication {

    

    public static function getAccessToken($appId,$appSecret,$permanentCode)
    {
        $key = "access_token" . $appId;
        $res = Redis::get($key);
        if ($res) {
            return $res['corpAccessToken'];
        }
        $url = 'https://open.fxiaoke.com/cgi/corpAccessToken/get/V2';
        $param = [
            'appId' => $appId,
            'appSecret' => $appSecret,
            'permanentCode' => $permanentCode,
        ];
        var_dump($param);
        exit();
        $res = Http::post($url, $param);
        if ($res && isset($res['errorCode']) && $res['errorCode'] == 0) {
            $res['expires_time'] = date('Y-m-d H:i:s',time()+$res['expiresIn']-200);
            Redis::set($key, $res,$res['expiresIn']-200);
            return $res;
        }
        throw new \Exception("获取corpAccessToken失败");
    }

    public static function getAuthenticationParam($config){
        echo "???";
        var_dump($config);
        list($appId,$appSecret,$permanentCode) = $config;
        var_dump([$appId,$appSecret,$permanentCode]);
        $data = self::getAccessToken($appId,$appSecret,$permanentCode);
        return [
            'corpAccessToken'=>$data['corpAccessToken'],
            'corpId'=>$data['corpId'],
        ];
    }
    public static function getUserAuthenticationParam($config,$mobile = ''){
        if($mobile){
            $config['mobile'] = $mobile;
        }
        $res = self::getAuthenticationParam($config);
        $openUserId = self::getCurrentOpenUserId($config);
        $res['currentOpenUserId'] = $openUserId;
        return $res;
    }
    public static function getCurrentOpenUserId($config){
        $openUserId = Redis::get($config['mobile']);
        if($openUserId){
            return $openUserId;
        }
        $url = 'https://open.fxiaoke.com/cgi/user/getByMobile';
        list($appId,$appSecret,$permanentCode) = $config;
        $param = self::getAccessToken($appId,$appSecret,$permanentCode);
        $param['mobile'] = $config['mobile'];
        $res = Http::post($url,$param);
        if ($res && isset($res['errorCode']) && $res['errorCode'] == 0) {
            Redis::set($config['mobile'],$res['empList'][0]['openUserId']);
            return $res['empList'][0]['openUserId'];
        }
        throw new \Exception("获取用户openUserId失败");
    }
}