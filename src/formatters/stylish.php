<?php

namespace Differ\formatters\stylish;

// use function Differ\differ\builder\transformObjectToArr;
use function Differ\differ\builder\boolOrNullToString;
use function Differ\differ\builder\stringify;
use function Differ\differ\builder\testStr;

// function buildStylish($arr, $depth = 0)
function stylish($arr, $depth = 0)
{
    $sep = str_repeat('    ', $depth);
    $res = array_map(function ($item) use ($sep, $depth) {
        $type = 'none';
        if(array_key_exists('type', $item)){
            $type = $item['type'];
        }
        // print_r($type);
        // switch ($item['type']) {
        switch ($type) {
            case 'nested':
                $children = stylish($item['children'], $depth + 1);
                return $sep . "    " . $item['name'] . " : " . $children . "\n";
            case 'unchanged':
                $unchanged = arrToStr($item['value'], $depth + 1);
                return $sep . "    " . $item['name'] . " : " . $unchanged . "\n";
            case 'changed':
                $transformedBefore = stringify($item['valueBefore']);
                $changedBefore = arrToStr($transformedBefore, $depth + 1);
                $transformedAfter = stringify($item['valueAfter']);
                $changedAfter = arrToStr($transformedAfter, $depth + 1);
                return $sep . "  - " . $item['name'] . " : " . $changedBefore . "\n" . $sep .
                "  + " . $item['name'] . " : " . $changedAfter . "\n";
            case 'removed':
                $transformed = stringify($item['value']);
                $removed = arrToStr($transformed, $depth + 1);
                return $sep . "  - " . $item['name'] . " : " . $removed . "\n";
            case 'added':
                $transformed = stringify($item['value']);
                $added = arrToStr($transformed, $depth + 1);
                return $sep . "  + " . $item['name'] . " : " . $added . "\n";
            // case 'return':
            //     $transformed = stringify($item['value']);
            //     $return = arrToStr($transformed, $depth + 1);
            //     return $sep . "    " . $item['name'] . " : " . $return . "\n";
            default:
                $transformed = stringify($item['value']);
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
    $first = 0;
    $last = count($tree) - 1;
    $tree[$first] = "{\n" . $tree[$first];
    $tree[$last] = $tree[$last] . $sep . "}";
    return $tree;
}
