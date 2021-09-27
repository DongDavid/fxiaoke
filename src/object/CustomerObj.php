<?php
namespace Dongdavid\Fxiaoke\object;

use Dongdavid\Fxiaoke\utils\Http;

class CustomerObj {

    const API_LIST = 'https://open.fxiaoke.com/cgi/crm/custom/v2/data/query';
    const API_DETAIL = 'https://open.fxiaoke.com/cgi/crm/custom/v2/data/get';
    const API_CREATE = 'https://open.fxiaoke.com/cgi/crm/custom/v2/data/create';
    const API_UPDATE = 'https://open.fxiaoke.com/cgi/crm/custom/v2/data/update';
    const API_DELETE = 'https://open.fxiaoke.com/cgi/crm/custom/v2/data/delete';
    const API_INVALID = 'https://open.fxiaoke.com/cgi/crm/custom/v2/data/invalid';
    const API_CHANGEOWNER = 'https://open.fxiaoke.com/cgi/crm/custom/v2/data/changeOwner';
    const API_LOCK = 'https://open.fxiaoke.com/cgi/crm/v2/object/lock';
    const API_UNLOCK = 'https://open.fxiaoke.com/cgi/crm/v2/object/unlock';

    private $apiName = '';
    private $err = [];

    public function getErr($last = true){
        if($last){
            return end($this->err);
        }
        return $this->err;
    }
    private function setErr($url,$param,$response){
        $this->err[] = [
            'url'=>$url,
            'param'=>$param,
            'response'=>$response,
            'msg'=>isset($response['errorMessage'])?$response['errorMessage']:'没有错误信息',
        ];
    }
    private function setParam($param){
        $base = [
            'corpAccessToken'=>'',
            'corpId'=>'',
            'currentOpenUserId'=>'',
        ];
        return array_merge($base,$param);
    }
    private function httpPost($url,$param){
        
        $param = $this->setParam($param);
        $res = Http::post($url,$param);

        if($res && isset($res['errorCode']) && $res['errorCode'] == 0){
            return $res;
        }
        $this->setErr($url,$param,$res);
        return false;
    }
    public function all($filters=[],$order = [],$fieldProjection=[],$find_explicit_total_num = false,$max = 0){

    }

    public function list($filters=[],$order = [],$fieldProjection=[],$find_explicit_total_num = false,$offset=0,$limit=100){
        $param = [
            'data'=>[
                'dataObjectApiName'=>$this->apiName,
                'search_query_info'=>[
                    'offset'=>$offset,
                    'limit'=>$limit,
                    'filters'=>$filters,
                    'order'=>$order,
                    'fieldProjection'=>$fieldProjection,
                    'find_explicit_total_num'=>$find_explicit_total_num,
                ],
            ]
        ];
        return $this->httpPost(self::API_LIST,$param);
    }

    public function create($object_data,$details=[],
        $triggerWorkFlow = true,
        $hasSpecifyTime = false,
        $hasSpecifyCreatedBy = false,
        $includeDetailIds = true){
        $param = [
            'data'=>[
                'dataObjectApiName'=>$this->apiName,
                'object_data'=>$object_data,
                'details'=>$details,
                'triggerWorkFlow'=>$triggerWorkFlow,
                'hasSpecifyTime'=>$hasSpecifyTime,
                'hasSpecifyCreatedBy'=>$hasSpecifyCreatedBy,
                'includeDetailIds'=>$includeDetailIds,
            ]
        ];
        return $this->httpPost(self::API_CREATE,$param);
    }

    public function detail($objectDataId){
        $param = [
            'data'=>[
                'dataObjectApiName'=>$this->apiName,
                'objectDataId'=>$objectDataId,
            ]
        ];
        return $this->httpPost(self::API_DETAIL,$param);
    }

    public function update($object_data,$triggerWorkFlow = true){
        $object_data['dataObjectApiName'] = $this->apiName;
        $param = [
            'data'=>[
                'triggerWorkFlow'=>$triggerWorkFlow,
                'object_data'=>$object_data,
            ]
        ];
        return $this->httpPost(self::API_UPDATE,$param);
    }
    public function delete($idList){
        $param = [
            'data'=>[
                'dataObjectApiName'=> $this->apiName,
                'idList'=>$idList,
            ]
        ];
        return $this->httpPost(self::API_UPDATE,$param);
    }
    public function invalid($object_data_id){
        $param = [
            'data'=>[
                'dataObjectApiName'=> $this->apiName,
                'object_data_id'=>$object_data_id,
            ]
        ];
        return $this->httpPost(self::API_INVALID,$param);
    }
    public function changeOwner($object_id,$ownerId){
        $param = [
            'data'=>[
                'dataObjectApiName'=> $this->apiName,
                'Data'=>[
                    [
                        'objectDataId'=>$object_id,
                        'ownerId'=>[
                            $ownerId
                        ],
                    ]
                ],
            ]
        ];
        return $this->httpPost(self::API_CHANGEOWNER,$param);
    }

    public function lock($dataIds,$detailObjStrategy = 1){
        $param = [
            'data'=>[
                'dataObjectApiName'=> $this->apiName,
                'dataIds'=>$dataIds,
                'detailObjStrategy'=>$detailObjStrategy,
            ]
        ];
        return $this->httpPost(self::API_LOCK,$param);
    }
    public function unlock($dataIds,$detailObjStrategy = 1){
        $param = [
            'data'=>[
                'dataObjectApiName'=> $this->apiName,
                'dataIds'=>$dataIds,
                'detailObjStrategy'=>$detailObjStrategy,
            ]
        ];
        return $this->httpPost(self::API_UNLOCK,$param);
    }
}