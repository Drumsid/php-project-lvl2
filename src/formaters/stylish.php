<?php

namespace Differ\formaters\stylish;

use function Differ\differ\builder\transformObjectToArr;
use function Differ\differ\builder\boolOrNullToString;

function stylish($arr, $deep = 0)
{
    $sep = str_repeat('    ', $deep);
    $res = array_map(function ($item) use ($sep, $deep) {
        switch ($item['type']) {
            case 'nested':
                $tmp = stylish($item['children'], $deep + 1);
                return $sep . "    " . $item['name'] . " : " . $tmp . "\n";
            case 'unchanged':
                $tmp = arrToStr($item['value'], $deep + 1);
                return $sep . "    " . $item['name'] . " : " . $tmp . "\n";
            case 'changed':
                $tempBefore = transformObjectToArr(boolOrNullToString($item['valueBefore']));
                $tempBefore = arrToStr($tempBefore, $deep + 1);
                $tempAfter = transformObjectToArr(boolOrNullToString($item['valueAfter']));
                $tempAfter = arrToStr($tempAfter, $deep + 1);
                return $sep . "  - " . $item['name'] . " : " . $tempBefore . "\n" . $sep .
                "  + " . $item['name'] . " : " . $tempAfter . "\n";
            case 'removed':
                $tmp = transformObjectToArr(boolOrNullToString($item['value']));
                $tmp = arrToStr($tmp, $deep + 1);
                return $sep . "  - " . $item['name'] . " : " . $tmp . "\n";
            case 'added':
                $tmp = transformObjectToArr(boolOrNullToString($item['value']));
                $tmp = arrToStr($tmp, $deep + 1);
                return $sep . "  + " . $item['name'] . " : " . $tmp . "\n";
            case 'return':
                $tmp = transformObjectToArr(boolOrNullToString($item['value']));
                $tmp = arrToStr($tmp, $deep + 1);
                return $sep . "    " . $item['name'] . " : " . $tmp . "\n";
        }
    }, $arr);
    return implode(addBrackets($res, $sep));
}

function arrToStr($arr, $deep)
{
    if (is_array($arr)) {
        return stylish($arr, $deep);
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
