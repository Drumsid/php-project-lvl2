<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\differ\genDiff\genDiff;
use function Differ\differ\Yamlic\parseYml;

class GetDeepDifferTest extends TestCase
{
    public function testGetDeepDiffJson()
    {
        $correctDiffJson = file_get_contents(__DIR__ . '/fixtures/json/correctDeepJson');
        $inCorrectDiffJson = "{incorrect:json}";
        $beforeJson = __DIR__ . "/../jsonTestFile/deepBefore.json";
        $afterJson = __DIR__ . "/../jsonTestFile/deepAfter.json";
        $result1 = genDiff($beforeJson, $afterJson);
        $this->assertSame($correctDiffJson, $result1);
        $this->assertNotSame($inCorrectDiffJson, $result1);
    }

    public function testGetDeepDiffYml()
    {
        $correctDiffYml = file_get_contents(__DIR__ . '/fixtures/yml/correctDeepYml');
        $inCorrectDiffYml = "{incorrect:json}";
        $beforeYml = __DIR__ . "/../ymlTestFile/deepBefore.yml";
        $afterYml = __DIR__ . "/../ymlTestFile/deepAfter.yml";
        $result2 = genDiff($beforeYml, $afterYml);
        $this->assertSame($correctDiffYml, $result2);
        $this->assertNotSame($inCorrectDiffYml, $result2);
    }
}