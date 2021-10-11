<?php
namespace Dongdavid\Fxiaoke;

use Dongdavid\Fxiaoke\object\CustomerObj;
use Dongdavid\Fxiaoke\object\AccountObj;

class Fxiaoke
{

    private array $config = [
        'appId'=>'',
        'appSecret'=>'',
        'permanentCode'=>'',
        'mobile'=>'',
    ];

    public function __construct($config){
        $this->config = $config;
    }

    public function customerObj($apiName): CustomerObj
    {
        return new CustomerObj($apiName,$this->config);
    }

    public function accountObj(): AccountObj
    {
        return new AccountObj($this->config);
    }


}
