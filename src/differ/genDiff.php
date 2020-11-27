<?php

namespace Differ\differ\genDiff;

use Symfony\Component\Yaml\Yaml;

use function Differ\differ\Parsers\deepDiff;
use function Differ\differ\Parsers\xDif;
use function Differ\differ\Parsers\boolOrNullToString;
use function Differ\differ\Parsers\stylish;
use function Differ\differ\Parsers\transformToArr;

const CORRECT_PATH = __DIR__ . "/../";

function correct_path_json($path)
{
    if (file_exists($path)) {
        return json_decode(file_get_contents($path));
    }
    return $path;
}

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

function genDiff($beforeJson, $afterJson)
{
    // $beforeJson = correct_path_json($beforeJson);
    // $afterJson = correct_path_json($afterJson);
    $beforeJson = checkExpansion($beforeJson);
    $afterJson = checkExpansion($afterJson);
// var_dump($beforeJson);
    if (! is_object($beforeJson)) {
        return "{$beforeJson} file not exists or path incorrect\n";
    }
    if (! is_object($afterJson)) {
        return "{$afterJson} file not exists or path incorrect\n";
    }
    $beforeJson = transformToArr($beforeJson);
    $afterJson = transformToArr($afterJson);

    $strJson = deepDiff($beforeJson, $afterJson);
    return stylish(xDif($strJson));
}

// print_r(genDiff('before.json', 'after.json'));
