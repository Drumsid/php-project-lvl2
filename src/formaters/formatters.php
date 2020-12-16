<?php

namespace Differ\formaters\formatters;

use function Differ\formaters\stylish\stylish;
use function Differ\formaters\plain\plain;

function checkFormat($format, $tree)
{
    if ($format == 'plain') {
        return plain($tree);
    }
    // if ($format == 'json') {
    //     return niceJsonView(xDif($strJson));
    // }
    return stylish($tree);
}
