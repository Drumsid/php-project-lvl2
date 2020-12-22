<?php

namespace Differ\formatters\formatters;

use function Differ\formatters\stylish\stylish;
use function Differ\formatters\plain\plain;
use function Differ\formatters\json\jsonFormat;

function renderFormat($format, $tree)
{
    switch ($format) {
        case 'plain':
            return plain($tree);
        case 'json':
            return jsonFormat($tree);
        default:
            return stylish($tree);
    }
}
