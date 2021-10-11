<?php

namespace Dongdavid\Fxiaoke;

class FxiaokeSdk
{
    private string $appId = '';
    private string $appSecret = '';
    private string $permanentCode = '';
    private string $mobile = '';
    private string $openUserId = '';
    private string $corpId = '';
    private $redis = null;
    private $redisConfig = [
        'host' => '127.0.0.1',
        'port' => '6379',
        'password' => '',
        'select' => 0,
        'timeout' => 3,
    ];

    public function __construct($appId,$appSecret,$permanentCode,$mobile = '')
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->permanentCode =$permanentCode;
        $this->mobile = $mobile;
    }

    public function getAccessToken(): string
    {
        $res = $this->cache($this->appId);
        if ($res){
            $this->corpId = $res['corpId'];
            return $res['corpAccessToken'];
        }
        $url = 'https://open.fxiaoke.com/cgi/corpAccessToken/get/V2';
        $param = [
            'appId' => $this->appId,
            'appSecret' => $this->appSecret,
            'permanentCode' => $this->permanentCode,
        ];
        $res = $this->httpPost($url,$param);
        $this->cache($this->appId,$res,$res['expires_time']);
        $this->corpId = $res['corpId'];
        return $res['corpAccessToken'];
    }

    public function objPreSetList(){
            
    }
    public function objCustomList(){
        
    }

    public function objCustomCreate()
    {
        
    }
    public function objCustomUpdate(){
        
    }
    
    public function getOpenUserId($mobile = '')
    {
        if (!$mobile){
            $mobile = $this->mobile;
        }
        $url = 'https://open.fxiaoke.com/cgi/user/getByMobile';
        
    }
    public function cache($key,$value = null,$expire_time = 0)
    {
        $redis = $this->getRedis();
        if ($value === null){
            $value = $redis->get($key);
            $json = json_decode($value,true);
            return $json?:$value;
        }else{
            if (!is_string($value)){
                $value = json_encode($value,JSON_UNESCAPED_UNICODE);
            }
            $redis->set($key,$value);
            if ($expire_time){
                $redis->expire($key,$expire_time);
            }
        }
    }
    private function getRedis(){
        if ($this->redis) {
            return $this->redis;
        }
        $config = $this->redisConfig;
        $this->redis = new \Redis();
        $this->redis->connect($config['host'], $config['port'], $config['timeout']);
        if (isset($config['password']) && !empty($config['password'])) {
            $this->redis->auth($config['password']);
        }
        if (isset($config['select'])) {
            $this->redis->select($config['select']);
        }
        return $this->redis;
    }
    public function httpPost($url,$param)
    {
        $timeout  = 1000;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($param));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //处理http证书问题
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //处理http证书问题
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        if (false === $result) {
            $result = curl_errno($ch);
        }
        curl_close($ch);
        return $result;
    }
}