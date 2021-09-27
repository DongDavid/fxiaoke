<?php

namespace Dongdavid\Fxiaoke\Tests;

use Dongdavid\Fxiaoke\utils\Redis;
use PHPUnit\Framework\TestCase;

class Redistest extends TestCase
{
    public function testExtension(){
        $this->assertEquals(extension_loaded('redis'),true,'redis扩展未安装');
        $this->assertEquals(class_exists('Redis'),true,'找不到redis基类');
    }
    public function testDelete(){
        Redis::del('tt');
        $this->assertNull(Redis::get('tt'),"不存在键值为tt的数据");
    }
    public function testGenerate(){
        Redis::del('tt');
        Redis::set('tt','33');
        $this->assertEquals('33',Redis::get('tt'),"tt的值应为33");
    }
    // public function testExpire(){
    //     Redis::del('tt');
    //     Redis::set('tt','88',3);
    //     sleep(2);
    //     $this->assertEquals('88',Redis::get('tt'),"tt的值应为33");
    //     sleep(2);
    //     $this->assertNull(Redis::get('tt'),"tt应已过期");
    // }
    
}