<?php

namespace Dongdavid\Fxiaoke\Tests;

use Dongdavid\Fxiaoke\Fxiaoke;
use PHPUnit\Framework\TestCase;

class FxiaokeTest extends TestCase
{
    public function testAccessToken(){
        $appId = 'FSAID_131a17e';
         $appSecret = '331495646595416b9dc37b87c830e6aa';
         $permanentCode = '67B36638B6E2A25495DA7C24975674B0';
         $cropId = 'FSCID_72F817C936B8E130D7913BF28DDC746B';
        $m = new Fxiaoke($appId,$appSecret,$permanentCode);
        $this->assertIsString($m->getAccessToken(),"未返回access_token");
    }
    
}
