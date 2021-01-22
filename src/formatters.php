<?php

namespace Differ\formatters;

function format($format, $tree)
{
    switch ($format) {
        case 'plain':
            return plain\render($tree);
        case 'json':
            return json\render($tree);
        case 'stylish':
            return stylish\render($tree);
        default:
            throw new \Exception("Unknown format: '{$format}'!");
    }
}
