<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\differ\genDiff\genDiff;
use function Differ\differ\Yamlic\parseYml;

class GetDifferTest extends TestCase
{
    public function testGetDiffJson()
    {
        $correctDiffJson = file_get_contents(__DIR__ . '/fixtures/json/correctSimpleJson');
        $inCorrectDiffJson = "{incorrect:json}";
        $beforeJson = __DIR__ . "/fixtures/jsonTestFile/before.json";
        $afterJson = __DIR__ . "/fixtures/jsonTestFile/after.json";
        $result1 = genDiff($beforeJson, $afterJson);
        $this->assertSame($correctDiffJson, $result1);
        $this->assertNotSame($inCorrectDiffJson, $result1);
    }

    public function testGetDiffYml()
    {
        $correctDiffYml = file_get_contents(__DIR__ . '/fixtures/yml/correctSimpleYml');
        $inCorrectDiffYml = "{incorrect:json}";
        $beforeYml = __DIR__ . "/fixtures/ymlTestFile/before.yml";
        $afterYml = __DIR__ . "/fixtures/ymlTestFile/after.yml";
        $result2 = genDiff($beforeYml, $afterYml);
        $this->assertSame($correctDiffYml, $result2);
        $this->assertNotSame($inCorrectDiffYml, $result2);
    }
}
