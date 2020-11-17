<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\differ\genDiff\genDiff;
use function Differ\differ\Yamlic\parseYml;

class DifferTest extends TestCase
{
    public function testGetDiffJson()
    {
        $correctDiffJson = file_get_contents(__DIR__ . '/fixtures/correctJsonDiff');
        $inCorrectDiffJson = "{incorrect:json}";
        $beforeJson = __DIR__ . "/../jsonTestFile/before.json";
        $afterJson = __DIR__ . "/../jsonTestFile/after.json";
        $result1 = genDiff($beforeJson, $afterJson);
        $this->assertSame($correctDiffJson, $result1);
        $this->assertNotSame($inCorrectDiffJson, $result1);
    }

    public function testGetDiffYml()
    {
        $correctDiffYml = file_get_contents(__DIR__ . '/fixtures/correctYmlDiff');
        $inCorrectDiffYml = "{incorrect:json}";
        $beforeYml = __DIR__ . "/../ymlTestFile/before.yml";
        $afterYml = __DIR__ . "/../ymlTestFile/after.yml";
        $result1 = parseYml($beforeYml, $afterYml);
        $this->assertSame($correctDiffYml, $result1);
        $this->assertNotSame($inCorrectDiffYml, $result1);
    }
}
