<?php

namespace Differ\formatters\stylish;

use function Differ\differ\builder\transformObjectToArr;
use function Differ\differ\builder\boolOrNullToString;

// function buildStylish($arr, $depth = 0)
function stylish($arr, $depth = 0)
{
    $sep = str_repeat('    ', $depth);
    $res = array_map(function ($item) use ($sep, $depth) {
        switch ($item['type']) {
            case 'nested':
                $children = stylish($item['children'], $depth + 1);
                return $sep . "    " . $item['name'] . " : " . $children . "\n";
            case 'unchanged':
                $unchanged = arrToStr($item['value'], $depth + 1);
                return $sep . "    " . $item['name'] . " : " . $unchanged . "\n";
            case 'changed':
                $transformedBefore = transformObjectToArr(boolOrNullToString($item['valueBefore']));
                $changedBefore = arrToStr($transformedBefore, $depth + 1);
                $transformedAfter = transformObjectToArr(boolOrNullToString($item['valueAfter']));
                $changedAfter = arrToStr($transformedAfter, $depth + 1);
                return $sep . "  - " . $item['name'] . " : " . $changedBefore . "\n" . $sep .
                "  + " . $item['name'] . " : " . $changedAfter . "\n";
            case 'removed':
                $transformed = transformObjectToArr(boolOrNullToString($item['value']));
                $removed = arrToStr($transformed, $depth + 1);
                return $sep . "  - " . $item['name'] . " : " . $removed . "\n";
            case 'added':
                $transformed = transformObjectToArr(boolOrNullToString($item['value']));
                $added = arrToStr($transformed, $depth + 1);
                return $sep . "  + " . $item['name'] . " : " . $added . "\n";
            case 'return':
                $transformed = transformObjectToArr(boolOrNullToString($item['value']));
                $return = arrToStr($transformed, $depth + 1);
                return $sep . "    " . $item['name'] . " : " . $return . "\n";
        }
    }, $arr);
    // print_r($res);
    return implode(addBrackets($res, $sep));
    // return addBrackets($res, $sep);
}
// function stylish($arr)
// {
//     return implode($arr);
// }

function arrToStr($arr, $depth)
{
    if (is_array($arr)) {
        return stylish($arr, $depth);
    } else {
        return $arr;
    }
}

function addBrackets($tree, $sep)
{
    $last = count($tree) - 1;
    $tree[0] = "{\n" . $tree[0];
    $tree[$last] = $tree[$last] . $sep . "}";
    return $tree;
}
