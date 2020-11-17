<?php

namespace Differ\differ\genDiff;

use function Differ\differ\Parsers\parsing;
use function Differ\differ\Parsers\correctCurleBrackets;
use function Differ\differ\Parsers\deepDiff;
use function Differ\differ\Parsers\xDif;
use function Differ\differ\Parsers\boolOrNullToString;
use function Differ\differ\Parsers\formatic;

const CORRECT_PATH = __DIR__ . "/../";

function correct_path_json($path)
{
    if (file_exists($path)) {
        return json_decode(file_get_contents($path), true);
    }
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

    $strJson = deepDiff($beforeJson, $afterJson);
    return formatic(xDif($strJson));
}

// print_r(genDiff('before.json', 'after.json'));
