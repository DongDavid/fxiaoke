<?php
namespace Dongdavid\Fxiaoke;

use Dongdavid\Fxiaoke\object\CustomerObj;
use Dongdavid\Fxiaoke\object\AccountObj;

class Fxiaoke
{

    private $config = [
        'appId'=>'',
        'appSecret'=>'',
        'permanentCode'=>'',
        'mobile'=>'',
    ];

    public function __construct($config){
        $this->config = $config;
    }
   
    public function customerObj($apiName){
        $customerObj = new CustomerObj($apiName,$this->config);
        return $customerObj;
    }

    public function accountObj(){
        $accountObj = new AccountObj($this->config);
        return $accountObj;
    }


}
