<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\differ\genDiff\genDiff;

class DifferTest extends TestCase
{
    public function testGetDiff()
    {
        $correctDiff = file_get_contents(__DIR__ . '/fixtures/correctJsonDiff');
        $inCorrectDiff = "{incorrect:json}";
        $path1 = __DIR__ . "/../before.json";
        $path2 = __DIR__ . "/../after.json";
        $result1 = genDiff($path1, $path2);
        $this->assertSame($correctDiff, $result1);
        $this->assertNotSame($inCorrectDiff, $result1);
    }
}
