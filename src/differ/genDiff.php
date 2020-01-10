<?php

namespace Differ\differ\genDiff;

use function Differ\differ\Parsers\parsing;
use function Differ\differ\Parsers\correctCurleBrackets;

const CORRECT_PATH = __DIR__ . "/../";

function correct_path($path)
{
    if (file_exists($path)) {
        return json_decode(file_get_contents($path), true);
    }
    if (file_exists($path)) {
        return json_decode(file_get_contents(CORRECT_PATH . $path), true);
    }
    return $path;
}

function genDiff($beforeJson, $afterJson)
{
    $beforeJson = correct_path($beforeJson);
    $afterJson = correct_path($afterJson);

    if (! is_array($beforeJson)) {
        return "{$beforeJson} file not exists or path incorrect\n";
    }
    if (! is_array($beforeJson)) {
        return "{$afterJson} file not exists or path incorrect\n";
    }

    $strJson = parsing($beforeJson, $afterJson);

    $tmp = correctCurleBrackets(str_replace(',', PHP_EOL, $strJson), PHP_EOL);
    return str_replace('"', "", $tmp);
}
