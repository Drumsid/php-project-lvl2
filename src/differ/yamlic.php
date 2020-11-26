<?php

namespace Differ\differ\Yamlic;

use Symfony\Component\Yaml\Yaml;

use function Differ\differ\Parsers\transformToArr;
use function Differ\differ\Parsers\deepDiff;
use function Differ\differ\Parsers\xDif;
use function Differ\differ\Parsers\boolOrNullToString;
use function Differ\differ\Parsers\stylish;

const CORRECT_PATH_YAML = __DIR__ . "/../../";

function correct_path_yml($path)
{
    if (file_exists($path)) {
        return Yaml::parseFile($path, Yaml::PARSE_OBJECT_FOR_MAP);
    }
    return $path;
}

function parseYml($beforeYml, $afterYml)
{
    $beforeYml = correct_path_yml($beforeYml);
    $afterYml = correct_path_yml($afterYml);

    if (! is_object($beforeYml)) {
        return "{$beforeYml} file not exists or path incorrect\n";
    }
    if (! is_object($afterYml)) {
        return "{$afterYml} file not exists or path incorrect\n";
    }

    $beforeYml = transformToArr($beforeYml);
    $afterYml = transformToArr($afterYml);

    $strJson = deepDiff($beforeYml, $afterYml);
    return stylish(xDif($strJson));
}

// print_r(parseYml("before.yml", "after.yml"));
