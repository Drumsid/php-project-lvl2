<?php

namespace Differ\differ\Yamlic;

use Symfony\Component\Yaml\Yaml;

use function Differ\differ\Parsers\parsing;
use function Differ\differ\Parsers\correctCurleBrackets;
use function Differ\differ\Parsers\deepDiff;
use function Differ\differ\Parsers\xDif;
use function Differ\differ\Parsers\boolOrNullToString;
use function Differ\differ\Parsers\formatic;

const CORRECT_PATH_YAML = __DIR__ . "/../../";

// $autoloadPath1 = __DIR__ . '/../../autoload.php';    // без этой записи в bin/gendiff функция parseYml работает
// $autoloadPath2 = __DIR__ . '/../../vendor/autoload.php';  // но если без этой записи вызвать
// if (file_exists($autoloadPath1)) {                       // в этом файле функцию parseYml - ошибка
//     require_once $autoloadPath1;
// } else {
//     require_once $autoloadPath2;
// }

function correct_path_yml($path)
{
    if (file_exists($path)) {
        return Yaml::parseFile($path, Yaml::PARSE_OBJECT_FOR_MAP);
    }
    if (file_exists($path)) {
        return Yaml::parseFile(CORRECT_PATH_YAML . $path, Yaml::PARSE_OBJECT_FOR_MAP);
    }
    return $path;
}

function parseYml($beforeYml, $afterYml)
{
    $beforeYml = json_encode(correct_path_yml($beforeYml));
    $afterYml = json_encode(correct_path_yml($afterYml));

    $beforeYml = json_decode($beforeYml, true);
    $afterYml = json_decode($afterYml, true);
    
    if (! is_array($beforeYml)) {
        return "{$beforeYml} file not exists or path incorrect\n";
    }
    if (! is_array($afterYml)) {
        return "{$afterYml} file not exists or path incorrect\n";
    }


    // $strJson = parsing($beforeYml, $afterYml);

    // $tmp = correctCurleBrackets(str_replace(',', PHP_EOL, $strJson), PHP_EOL);
    // return str_replace('"', "", $tmp);
    $strJson = deepDiff($beforeYml, $afterYml);
    // print_r($strJson);
    return formatic(xDif($strJson));
}

// print_r(parseYml("before.yml", "after.yml"));
