<?php

namespace Differ\differ\genDiff;

use function Differ\differ\Parsers\deepDiff;
use function Differ\differ\Parsers\checkExpansion;
use function Differ\formaters\Stylish\xDif;
use function Differ\differ\Parsers\boolOrNullToString;
use function Differ\formaters\Stylish\stylish;
use function Differ\differ\Parsers\transformToArr;
use function Differ\formaters\Plain\plain;
use function Differ\formaters\Json\niceJsonView;

function genDiff($beforeJson, $afterJson, $format = 'stylish')
{
    $beforeJson = checkExpansion($beforeJson);
    $afterJson = checkExpansion($afterJson);

    $beforeJson = transformToArr($beforeJson);
    $afterJson = transformToArr($afterJson);

    $strJson = deepDiff($beforeJson, $afterJson);
    if ($format == 'plain') {
        return plain($strJson);
    }
    if ($format == 'json') {
        return niceJsonView(xDif($strJson));
    }
    return stylish(xDif($strJson));
}
