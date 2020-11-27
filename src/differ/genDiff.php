<?php

namespace Differ\differ\genDiff;

use Symfony\Component\Yaml\Yaml;

use function Differ\differ\Parsers\deepDiff;
use function Differ\formaters\Stylish\xDif;
use function Differ\differ\Parsers\boolOrNullToString;
use function Differ\formaters\Stylish\stylish;
use function Differ\differ\Parsers\transformToArr;
use function Differ\formaters\Plain\plain;

const CORRECT_PATH = __DIR__ . "/../";

function checkExpansion($file)
{
    if (substr($file, -4) == ".yml" && file_exists($file)) {
        return Yaml::parseFile($file, Yaml::PARSE_OBJECT_FOR_MAP);
    }
    if (substr($file, -5) == ".json" && file_exists($file)) {
        return json_decode(file_get_contents($file));
    }
    return $file;
}

function genDiff($beforeJson, $afterJson, $format = 'stylish')
{
    $beforeJson = checkExpansion($beforeJson);
    $afterJson = checkExpansion($afterJson);

    if (! is_object($beforeJson)) {
        return "{$beforeJson} file not exists or path incorrect\n";
    }
    if (! is_object($afterJson)) {
        return "{$afterJson} file not exists or path incorrect\n";
    }
    $beforeJson = transformToArr($beforeJson);
    $afterJson = transformToArr($afterJson);

    $strJson = deepDiff($beforeJson, $afterJson);
    if ($format == 'plain') {
        return plain($strJson);
    }
    return stylish(xDif($strJson));
}

// print_r(genDiff('before.json', 'after.json'));
