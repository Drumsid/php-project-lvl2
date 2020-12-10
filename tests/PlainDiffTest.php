<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\differ\genDiff\genDiff;

class PlainDiffTest extends TestCase
{
    public function testGetPlainDiffJson()
    {
        $correctPlainDiffJson = file_get_contents(__DIR__ . '/fixtures/plain/plainDiffJson');
        $inCorrectDiffJson = "{incorrect:json}";
        $beforeJson = __DIR__ . "/fixtures/jsonTestFile/before.json";
        $afterJson = __DIR__ . "/fixtures/jsonTestFile/after.json";
        $result1 = genDiff($beforeJson, $afterJson, 'plain');
        $this->assertSame($correctPlainDiffJson, $result1);
        $this->assertNotSame($inCorrectDiffJson, $result1);
    }

    public function testGetPlainDeepDiffJson()
    {
        $correctPlainDeepDiffJson = file_get_contents(__DIR__ . '/fixtures/plain/plainDeepDiffJson');
        $inCorrectDiffJson = "{incorrect:json}";
        $beforeDeepJson = __DIR__ . "/fixtures/jsonTestFile/deepBefore.json";
        $afterDeepJson = __DIR__ . "/fixtures/jsonTestFile/deepAfter.json";
        $result1 = genDiff($beforeDeepJson, $afterDeepJson, 'plain');
        $this->assertSame($correctPlainDeepDiffJson, $result1);
        $this->assertNotSame($inCorrectDiffJson, $result1);
    }

    public function testGetPlainDiffYml()
    {
        $correctPlainDiffYml = file_get_contents(__DIR__ . '/fixtures/plain/plainDiffYml');
        $inCorrectDiffJson = "{incorrect:json}";
        $beforeYml = __DIR__ . "/fixtures/ymlTestFile/before.yml";
        $afterYml = __DIR__ . "/fixtures/ymlTestFile/after.yml";
        $result1 = genDiff($beforeYml, $afterYml, 'plain');
        $this->assertSame($correctPlainDiffYml, $result1);
        $this->assertNotSame($inCorrectDiffJson, $result1);
    }

    public function testGetPlainDeepDiffYml()
    {
        $correctPlainDeepDiffYml = file_get_contents(__DIR__ . '/fixtures/plain/plainDeepDiffYml');
        $inCorrectDiffJson = "{incorrect:json}";
        $beforeDeepYml = __DIR__ . "/fixtures/ymlTestFile/deepBefore.yml";
        $afterDeepYml = __DIR__ . "/fixtures/ymlTestFile/deepAfter.yml";
        $result1 = genDiff($beforeDeepYml, $afterDeepYml, 'plain');
        $this->assertSame($correctPlainDeepDiffYml, $result1);
        $this->assertNotSame($inCorrectDiffJson, $result1);
    }
}
