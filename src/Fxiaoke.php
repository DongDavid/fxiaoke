<?php
namespace Dongdavid\Fxiaoke;

use Dongdavid\Fxiaoke\object\CustomerObj;
use Dongdavid\Fxiaoke\utils\Http;
use Dongdavid\Fxiaoke\utils\Redis;

class Fxiaoke
{

    private $appId = '';
    private $appSecret = '';
    private $permanentCode = '';
    private $corpId = '';

    public function __construct($appId = '', $appSecret = '', $permanentCode = '')
    {
        $appId && $this->appId = $appId;
        $appSecret && $this->appSecret = $appSecret;
        $permanentCode && $this->permanentCode = $permanentCode;
    }
    public function setConfig($appId = '', $appSecret = '', $permanentCode = '')
    {
        $appId && $this->appId = $appId;
        $appSecret && $this->appSecret = $appSecret;
        $permanentCode && $this->permanentCode = $permanentCode;
    }

    public function getAccessToken()
    {
        $key = "access_token" . $this->appId;
        $res = Redis::get($key);
        if ($res) {
            return $res['corpAccessToken'];
        }
        $url = 'https://open.fxiaoke.com/cgi/corpAccessToken/get/V2';
        $param = [
            'appId' => $this->appId,
            'appSecret' => $this->appSecret,
            'permanentCode' => $this->permanentCode,
        ];
        $res = Http::post($url, $param);
        if ($res && isset($res['errorCode']) && $res['errorCode'] == 0) {
            $this->corpId = $res['corpId'];
        }
        $res['expires_time'] = date('Y-m-d H:i:s',time()+$res['expiresIn']-200);
        Redis::set($key, $res,$res['expiresIn']-200);
        // Redis::set('time',[time(),$res['expiresIn'],time()+$res['expiresIn']-200,date('Y-m-d H:i:s',time()+$res['expiresIn']-200)]);
        return $res['corpAccessToken'];
    }
    public function CustomerObj(){
        $customerObj = new CustomerObj();
        return $customerObj;
    }
}
