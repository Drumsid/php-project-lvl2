<?php

namespace Differ\differ\genDiff;

use function Differ\differ\Parsers\parsing;
use function Differ\differ\Parsers\correctCurleBrackets;
use function Differ\differ\Parsers\deepDiff;
use function Differ\differ\Parsers\xDif;
use function Differ\differ\Parsers\boolOrNullToString;
use function Differ\differ\Parsers\formatic;


const CORRECT_PATH = __DIR__ . "/../";

function correct_path_json($path) //эту функцию надо писать заново
{
    // print_r(__DIR__);
    if (file_exists($path)) {
        // print_r($path . "1\n");
        return json_decode(file_get_contents($path), true);
    }
    // почему тут так написано не пойму, одна и та же проверка if (file_exists($path))  но работает
    if (file_exists($path)) {
        // print_r($path . "2\n");
        return json_decode(file_get_contents(CORRECT_PATH . $path), true);
    }
    // print_r($path . "3\n");
    return $path;
}

function genDiff($beforeJson, $afterJson)
{
    $beforeJson = correct_path_json($beforeJson);
    $afterJson = correct_path_json($afterJson);

    if (! is_array($beforeJson)) {
        return "{$beforeJson} file not exists or path incorrect\n";
    }
    if (! is_array($afterJson)) {
        return "{$afterJson} file not exists or path incorrect\n";
    }

    // $strJson = parsing($beforeJson, $afterJson);
    $strJson = deepDiff($beforeJson, $afterJson);
    return formatic(xDif($strJson));

    // $tmp = correctCurleBrackets(str_replace(',', PHP_EOL, $strJson), PHP_EOL);
    // return str_replace('"', "", $tmp);



}

// print_r(genDiff('before.json', 'after.json'));