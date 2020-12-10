<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\differ\genDiff\genDiff;

class JsonDifferTest extends TestCase
{
    public function testJsonToJSonDiffer()
    {
        $correctDiffJson = file_get_contents(__DIR__ . '/fixtures/json/correctJsonToJson');
        $inCorrectDiffJson = "{incorrect:json}";
        $beforeJson = __DIR__ . "/fixtures/jsonTestFile/before.json";
        $afterJson = __DIR__ . "/fixtures/jsonTestFile/after.json";
        $result1 = genDiff($beforeJson, $afterJson, 'json');
        $this->assertSame($correctDiffJson, $result1);
        $this->assertNotSame($inCorrectDiffJson, $result1);       
    }
    public function testJsonToJSonDeepDiffer()
    {
        $correctDiffJson = file_get_contents(__DIR__ . '/fixtures/json/correctDeepJsonToJson');
        $inCorrectDiffJson = "{incorrect:json}";
        $beforeJson = __DIR__ . "/fixtures/jsonTestFile/deepBefore.json";
        $afterJson = __DIR__ . "/fixtures/jsonTestFile/deepAfter.json";
        $result1 = genDiff($beforeJson, $afterJson, 'json');
        $this->assertSame($correctDiffJson, $result1);
        $this->assertNotSame($inCorrectDiffJson, $result1);       
    }
    public function testYmlToJSonDiffer()
    {
        $correctDiffJson = file_get_contents(__DIR__ . '/fixtures/json/correctYmlToJson');
        $inCorrectDiffJson = "{incorrect:json}";
        $beforeJson = __DIR__ . "/fixtures/jsonTestFile/before.json";
        $afterJson = __DIR__ . "/fixtures/jsonTestFile/after.json";
        $result1 = genDiff($beforeJson, $afterJson, 'json');
        $this->assertSame($correctDiffJson, $result1);
        $this->assertNotSame($inCorrectDiffJson, $result1);       
    }
    public function testDeepYmlToJSonDiffer()
    {
        $correctDiffJson = file_get_contents(__DIR__ . '/fixtures/json/correctDeepYmlToJson');
        $inCorrectDiffJson = "{incorrect:json}";
        $beforeJson = __DIR__ . "/fixtures/jsonTestFile/deepBefore.json";
        $afterJson = __DIR__ . "/fixtures/jsonTestFile/deepAfter.json";
        $result1 = genDiff($beforeJson, $afterJson, 'json');
        $this->assertSame($correctDiffJson, $result1);
        $this->assertNotSame($inCorrectDiffJson, $result1);       
    }
}
