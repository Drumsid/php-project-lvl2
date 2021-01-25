<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

// use function Differ\genDiff\genDiff;
use function Differ\Differ\genDiff;

class GetDifferTest extends TestCase
{
    public function testJson()
    {
        $correctDiff = $this->getPathToFixture('correctDiff');
        $inCorrectDiff = $this->getPathToFixture('incorrectResult');
        $beforeJson = $this->getPathToFixture('before.json');
        $afterJson = $this->getPathToFixture('after.json');
        $result = genDiff($beforeJson, $afterJson);
        $this->assertStringEqualsFile($correctDiff, $result);
        $this->assertStringNotEqualsFile($inCorrectDiff, $result);
    }

    public function testYml()
    {
        $correctDiff = $this->getPathToFixture('correctDiff');
        $inCorrectDiff = $this->getPathToFixture('incorrectResult');
        $beforeYml = $this->getPathToFixture('before.yml');
        $afterYml = $this->getPathToFixture('after.yml');
        $result = genDiff($beforeYml, $afterYml);
        $this->assertStringEqualsFile($correctDiff, $result);
        $this->assertStringNotEqualsFile($inCorrectDiff, $result);
    }
    public function testPlainJson()
    {
        $correctPlain = $this->getPathToFixture('correctPlain');
        $inCorrectDiff = $this->getPathToFixture('incorrectResult');
        $beforeJson = $this->getPathToFixture('before.json');
        $afterJson = $this->getPathToFixture('after.json');
        $result = genDiff($beforeJson, $afterJson, 'plain');
        $this->assertStringEqualsFile($correctPlain, $result);
        $this->assertStringNotEqualsFile($inCorrectDiff, $result);
    }
    public function testPlainYml()
    {
        $correctPlain = $this->getPathToFixture('correctPlain');
        $inCorrectDiff = $this->getPathToFixture('incorrectResult');
        $beforeYml = $this->getPathToFixture('before.yml');
        $afterYml = $this->getPathToFixture('after.yml');
        $result = genDiff($beforeYml, $afterYml, 'plain');
        $this->assertStringEqualsFile($correctPlain, $result);
        $this->assertStringNotEqualsFile($inCorrectDiff, $result);
    }
    public function testJsonToJSon()
    {
        $correctDiff = $this->getPathToFixture('correctJson');
        $inCorrectDiff = $this->getPathToFixture('incorrectResult');
        $beforeJson = $this->getPathToFixture('before.json');
        $afterJson = $this->getPathToFixture('after.json');
        $result = genDiff($beforeJson, $afterJson, 'json');
        $this->assertStringEqualsFile($correctDiff, $result);
        $this->assertStringNotEqualsFile($inCorrectDiff, $result);
    }
    public function testYmlToJSon()
    {
        $correctDiff = $this->getPathToFixture('correctJson');
        $inCorrectDiff = $this->getPathToFixture('incorrectResult');
        $beforeJson = $this->getPathToFixture('before.yml');
        $afterJson = $this->getPathToFixture('after.yml');
        $result = genDiff($beforeJson, $afterJson, 'json');
        $this->assertStringEqualsFile($correctDiff, $result);
        $this->assertStringNotEqualsFile($inCorrectDiff, $result);
    }

    private function getPathToFixture($fixtureName)
    {
        return __DIR__ . "/fixtures/{$fixtureName}";
    }
}
