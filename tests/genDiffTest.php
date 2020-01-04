<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\differ\genDiff\genDiff;

class DifferTest extends TestCase
{
    public function testGetDiff()
    {
        $afterJsonTest = '{
            "timeout": 20,
            "verbose": true,
            "host": "hexlet.io"
          }';
        $beforeJsonTest = '{
            "host": "hexlet.io",
            "timeout": 50,
            "proxy": "123.234.53.22"
          }';
        $correctDiff = '{
            host: hexlet.io
            + timeout: 20
            - timeout: 50
            - proxy: 123.234.53.22
            + verbose: true
        }';
        $path1 = __DIR__ . "/../before.json";
        $path2 = __DIR__ . "/../after.json";
        $result1 = genDiff($path1, $path2);
        $this->assertNotEquals($correctDiff, $result1);
    }
}
