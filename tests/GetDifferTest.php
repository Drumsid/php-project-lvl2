<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\genDiff\genDiff;

class GetDifferTest extends TestCase
{
    public function testJson()
    {
        $correctDiff = file_get_contents(__DIR__ . '/fixtures/correctDiff');
        $inCorrectDiff = "{incorrect:json}";
        $beforeJson = __DIR__ . "/fixtures/before.json";
        $afterJson = __DIR__ . "/fixtures/after.json";
        $result = genDiff($beforeJson, $afterJson);
        $this->assertSame($correctDiff, $result);
        $this->assertNotSame($inCorrectDiff, $result);
    }

    public function testYml()
    {
        $correctDiff = file_get_contents(__DIR__ . '/fixtures/correctDiff');
        $inCorrectDiff = "{incorrect:json}";
        $beforeYml = __DIR__ . "/fixtures/before.yml";
        $afterYml = __DIR__ . "/fixtures/after.yml";
        $result = genDiff($beforeYml, $afterYml);
        $this->assertSame($correctDiff, $result);
        $this->assertNotSame($inCorrectDiff, $result);
    }
    public function testPlainJson()
    {
        $correctPlain = file_get_contents(__DIR__ . '/fixtures/correctPlain');
        $inCorrectDiff = "{incorrect:json}";
        $beforeJson = __DIR__ . "/fixtures/before.json";
        $afterJson = __DIR__ . "/fixtures/after.json";
        $result = genDiff($beforeJson, $afterJson, 'plain');
        $this->assertSame($correctPlain, $result);
        $this->assertNotSame($inCorrectDiff, $result);
    }
    public function testPlainYml()
    {
        $correctPlain = file_get_contents(__DIR__ . '/fixtures/correctPlain');
        $inCorrectDiff = "{incorrect:json}";
        $beforeYml = __DIR__ . "/fixtures/before.yml";
        $afterYml = __DIR__ . "/fixtures/after.yml";
        $result = genDiff($beforeYml, $afterYml, 'plain');
        $this->assertSame($correctPlain, $result);
        $this->assertNotSame($inCorrectDiff, $result);
    }
    public function testJsonToJSon()
    {
        $correctDiff = file_get_contents(__DIR__ . '/fixtures/correctJson');
        $inCorrectDiff = "{incorrect:json}";
        $beforeJson = __DIR__ . "/fixtures/before.json";
        $afterJson = __DIR__ . "/fixtures/after.json";
        $result = genDiff($beforeJson, $afterJson, 'json');
        $this->assertSame($correctDiff, $result);
        $this->assertNotSame($inCorrectDiff, $result);
    }
    public function testYmlToJSon()
    {
        $correctDiff = file_get_contents(__DIR__ . '/fixtures/correctJson');
        $inCorrectDiff = "{incorrect:json}";
        $beforeJson = __DIR__ . "/fixtures/before.yml";
        $afterJson = __DIR__ . "/fixtures/after.yml";
        $result = genDiff($beforeJson, $afterJson, 'json');
        $this->assertSame($correctDiff, $result);
        $this->assertNotSame($inCorrectDiff, $result);
    }
}
