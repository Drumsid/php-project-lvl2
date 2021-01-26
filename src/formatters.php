<?php

namespace Differ\Formatters;

function format(string $format, array $tree): string
{
    switch ($format) {
        case 'plain':
            return Plain\render($tree);
        case 'json':
            return Json\render($tree);
        case 'stylish':
            return Stylish\render($tree);
        default:
            throw new \Exception("Unknown format: '{$format}'!");
    }
}
