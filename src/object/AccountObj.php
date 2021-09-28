<?php
namespace Dongdavid\Fxiaoke\object;

use Dongdavid\Fxiaoke\object\traits\PresetObj;

class AccountObj {

    protected $apiName = 'AccountObj';
    use PresetObj;
    
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