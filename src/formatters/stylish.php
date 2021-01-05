<?php

namespace Differ\formatters\stylish;

use function Differ\builder\stringify;

function stylish($arr, $depth = 0)
{
    $sep = str_repeat('    ', $depth);
    $res = array_map(function ($item) use ($sep, $depth) {
        $type = 'none';
        if (array_key_exists('type', $item)) {
            $type = $item['type'];
        }
        switch ($type) {
            case 'nested':
                $children = stylish($item['children'], $depth + 1);
                return $sep . "    " . $item['name'] . " : " . $children . "\n";
            case 'unchanged':
                $unchanged = $item['value'];
                return $sep . "    " . $item['name'] . " : " . $unchanged . "\n";
            case 'changed':
                $changedBefore = arrToStr(stringify($item['valueBefore']), $depth + 1);
                $changedAfter = arrToStr(stringify($item['valueAfter']), $depth + 1);
                return $sep . "  - " . $item['name'] . " : " . $changedBefore . "\n" . $sep .
                "  + " . $item['name'] . " : " . $changedAfter . "\n";
            case 'removed':
                $removed = arrToStr(stringify($item['value']), $depth + 1);
                return $sep . "  - " . $item['name'] . " : " . $removed . "\n";
            case 'added':
                $added = arrToStr(stringify($item['value']), $depth + 1);
                return $sep . "  + " . $item['name'] . " : " . $added . "\n";
        }
    }, $arr);
    if (is_array($res)) {
        return implode(addBrackets($res, $sep));
    }
    return $res;
}
function arrToStr($arr, $depth)
{
    $sep = str_repeat('    ', $depth);
    if (!is_array($arr)) {
        return $arr;
    }
    $res = array_map(function ($node) use ($depth, $sep) {
        if (is_array($node['value'])) {
            return $sep . "    " . $node['name'] . " : " . arrToStr($node['value'], $depth + 1) . "\n";
        } else {
            return $sep . "    " . $node['name'] . " : " . $node['value'] . "\n";
        }
    }, $arr);
    return implode(addBrackets($res, $sep));
}
function addBrackets($tree, $sep)
{
    $first = 0;
    $last = count($tree) - 1;
    $tree[$first] = "{\n" . $tree[$first];
    $tree[$last] = $tree[$last] . $sep . "}";
    return $tree;
}
