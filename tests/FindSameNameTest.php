<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\differ\Parsers\findSameName;

class FindSameNameTest extends TestCase
{
    public function testfindSameName1()
    {
        $findArr = ['name' => 'name'];
        $dataArrs = [1,2,3,['name' => 'name', 'test' => 555],5,6,7,8];
        $correctAnswer = ['name' => 'name', 'test' => 555];
        $result1 = findSameName($findArr, $dataArrs);
        $this->assertSame($correctAnswer, $result1);
    }
    
    public function testfindSameName2()
    {
        $findArr = ['name' => 'name'];
        $dataArrs = [1,2,3,['name' => 'name', 'test' => 555],5,6,7,8];
        $result2 = findSameName($findArr, $dataArrs);
        $this->assertNotSame($findArr, $result2);
    }
    
    public function testfindSameName3()
    {
        $findArr = null;
        $dataArrs = [1,2,3,['name' => 'name', 'test' => 555],5,6,7,8];
        $result3 = findSameName($findArr, $dataArrs);
        $this->assertNotTrue($result3);
    }
    
    public function testfindSameName4()
    {
        $findArr = ['name' => 'name'];
        $dataArrs = [1,2,3,5,6,7,8];
        $result4 = findSameName($findArr, $dataArrs);
        $this->assertNotTrue($result4);
    }
    public function testfindSameName5()
    {
        $findArr = ['name' => 'name'];
        $dataArrs = null;
        $result5 = findSameName($findArr, $dataArrs);
        $this->assertNotTrue($result5);
    }
}
