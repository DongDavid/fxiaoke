<?php
namespace Dongdavid\Fxiaoke\object;

use Dongdavid\Fxiaoke\object\traits\PreSetObj;

class AccountObj {

    protected $config = [];
    protected $apiName = 'AccountObj';
    use PreSetObj;
    
    public function __construct($config)
    {
        $this->config = $config;
    }
    public function update($object_data, $triggerWorkFlow = true,$skipFunctionAction = false)
    {
        $object_data['dataObjectApiName'] = $this->apiName;
        $param = [
            'data' => [
                'triggerWorkFlow' => $triggerWorkFlow,
                'skipFunctionAction'=> $skipFunctionAction,
                'object_data' => $object_data,
            ]
        ];
        return $this->httpPost($this->urlUpdate, $param);
    }

}