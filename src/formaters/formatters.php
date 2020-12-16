<?php

namespace Differ\formaters\formatters;

use function Differ\formaters\stylish\stylish;
use function Differ\formaters\plain\plain;
use function Differ\formaters\json\jsonFormat;

function checkFormat($format, $tree)
{
    if ($format == 'plain') {
        return plain($tree);
    }
    if ($format == 'json') {
        return json_encode(jsonFormat($tree));
    }
    return stylish($tree);
}
