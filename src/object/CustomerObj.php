<?php
namespace Dongdavid\Fxiaoke\object;

use Dongdavid\Fxiaoke\object\traits\CustomObj;
use Dongdavid\Fxiaoke\utils\Http;
use Dongdavid\Fxiaoke\Authentication;

class CustomerObj {

    use CustomObj;
    protected $config = [];

    public function __construct($apiName,$config)
    {
        $this->apiName = $apiName;
    }

    public function setBaseParam($param){

    }

}