<?php

namespace Dongdavid\Fxiaoke\object\traits;

use Dongdavid\Fxiaoke\utils\Http;

/**
 * 
 */
trait CustomObj
{
    protected $urlQuery = 'https://open.fxiaoke.com/cgi/crm/custom/v2/data/query';
    protected $urlGet = 'https://open.fxiaoke.com/cgi/crm/custom/v2/data/get';
    protected $urlCreate = 'https://open.fxiaoke.com/cgi/crm/custom/v2/data/create';
    protected $urlUpdate = 'https://open.fxiaoke.com/cgi/crm/custom/v2/data/update';
    protected $urlDelete = 'https://open.fxiaoke.com/cgi/crm/custom/v2/data/delete';
    protected $urlInvalid = 'https://open.fxiaoke.com/cgi/crm/custom/v2/data/invalid';
    protected $urlChangeowner = 'https://open.fxiaoke.com/cgi/crm/custom/v2/data/changeOwner';
    protected $urlLock = 'https://open.fxiaoke.com/cgi/crm/v2/object/lock';
    protected $urlUnlock = 'https://open.fxiaoke.com/cgi/crm/v2/object/unlock';

    protected $apiName = '';

    private $err = [];

    public function getErr($last = true)
    {
        if ($last) {
            return end($this->err);
        }
        return $this->err;
    }
    private function setErr($url, $param, $response)
    {
        $this->err[] = [
            'url' => $url,
            'param' => $param,
            'response' => $response,
            'msg' => isset($response['errorMessage']) ? $response['errorMessage'] : '没有错误信息',
        ];
    }
    private function setParam($param)
    {
        $base = [
            'corpAccessToken' => '',
            'corpId' => '',
            'currentOpenUserId' => '',
        ];
        return array_merge($base, $param);
    }
    private function httpPost($url, $param)
    {

        $param = $this->setParam($param);
        $res = Http::post($url, $param);

        if ($res && isset($res['errorCode']) && $res['errorCode'] == 0) {
            return $res;
        }
        $this->setErr($url, $param, $res);
        return false;
    }
    public function all($filters = [], $order = [], $fieldProjection = [], $find_explicit_total_num = false, $max = 0)
    {
    }

    public function list($filters = [], $order = [], $fieldProjection = [], $find_explicit_total_num = false, $offset = 0, $limit = 100)
    {
        $param = [
            'data' => [
                'dataObjectApiName' => $this->apiName,
                'search_query_info' => [
                    'offset' => $offset,
                    'limit' => $limit,
                    'filters' => $filters,
                    'order' => $order,
                    'fieldProjection' => $fieldProjection,
                    'find_explicit_total_num' => $find_explicit_total_num,
                ],
            ]
        ];
        return $this->httpPost($this->urlQuery, $param);
    }

    public function create(
        $object_data,
        $details = [],
        $triggerWorkFlow = true,
        $hasSpecifyTime = false,
        $hasSpecifyCreatedBy = false,
        $includeDetailIds = true
    ) {
        $param = [
            'data' => [
                'dataObjectApiName' => $this->apiName,
                'object_data' => $object_data,
                'details' => $details,
                'triggerWorkFlow' => $triggerWorkFlow,
                'hasSpecifyTime' => $hasSpecifyTime,
                'hasSpecifyCreatedBy' => $hasSpecifyCreatedBy,
                'includeDetailIds' => $includeDetailIds,
            ]
        ];
        return $this->httpPost($this->urlCreate, $param);
    }

    public function get($objectDataId)
    {
        $param = [
            'data' => [
                'dataObjectApiName' => $this->apiName,
                'objectDataId' => $objectDataId,
            ]
        ];
        return $this->httpPost($this->urlGet, $param);
    }

    public function update($object_data, $triggerWorkFlow = true)
    {
        $object_data['dataObjectApiName'] = $this->apiName;
        $param = [
            'data' => [
                'triggerWorkFlow' => $triggerWorkFlow,
                'object_data' => $object_data,
            ]
        ];
        return $this->httpPost($this->urlUpdate, $param);
    }
    public function delete($idList)
    {
        $param = [
            'data' => [
                'dataObjectApiName' => $this->apiName,
                'idList' => $idList,
            ]
        ];
        return $this->httpPost($this->urlDelete, $param);
    }
    public function invalid($object_data_id)
    {
        $param = [
            'data' => [
                'dataObjectApiName' => $this->apiName,
                'object_data_id' => $object_data_id,
            ]
        ];
        return $this->httpPost($this->urlInvalid, $param);
    }
    public function changeOwner($object_id, $ownerId)
    {
        $param = [
            'data' => [
                'dataObjectApiName' => $this->apiName,
                'Data' => [
                    [
                        'objectDataId' => $object_id,
                        'ownerId' => [
                            $ownerId
                        ],
                    ]
                ],
            ]
        ];
        return $this->httpPost($this->urlChangeowner, $param);
    }

    public function lock($dataIds, $detailObjStrategy = 1)
    {
        $param = [
            'data' => [
                'dataObjectApiName' => $this->apiName,
                'dataIds' => $dataIds,
                'detailObjStrategy' => $detailObjStrategy,
            ]
        ];
        return $this->httpPost($this->urlLock, $param);
    }
    public function unlock($dataIds, $detailObjStrategy = 1)
    {
        $param = [
            'data' => [
                'dataObjectApiName' => $this->apiName,
                'dataIds' => $dataIds,
                'detailObjStrategy' => $detailObjStrategy,
            ]
        ];
        return $this->httpPost($this->urlUnlock, $param);
    }
}
