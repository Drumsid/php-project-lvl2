<?php

namespace Differ\formatters\stylish;

function stylish($arr, $depth = 0)
{
    $sep = str_repeat('    ', $depth);
    $stylishData = array_map(function ($item) use ($sep, $depth) {
        $type = $item['type'];
        $name = $item['name'];
        switch ($type) {
            case 'nested':
                $children = stylish($item['children'], $depth + 1);
                return "{$sep}    {$name} : {$children}\n";
            case 'unchanged':
                $unchanged = $item['value'];
                return "{$sep}    {$name} : {$unchanged}\n";
            case 'changed':
                $changedBefore = stringify($item['valueBefore'], $depth + 1);
                $changedAfter = stringify($item['valueAfter'], $depth + 1);
                return "{$sep}  - {$name} : {$changedBefore}\n{$sep}  + {$name} : {$changedAfter}\n";
            case 'removed':
                $removed = stringify($item['value'], $depth + 1);
                return "{$sep}  - {$name} : {$removed}\n";
            case 'added':
                $added = stringify($item['value'], $depth + 1);
                return "{$sep}  + {$name} : {$added}\n";
        }
    }, $arr);
    if (is_array($stylishData)) {
        return implode(addBrackets($stylishData, $sep));
    }
    return $stylishData;
}
function preparation($data)
{
    if (is_null($data)) {
        return 'null';
    }
    if (is_bool($data)) {
        return ($data === true) ? 'true' : 'false';
    }
    if (! is_object($data)) {
        return $data;
    }
    $obj = get_object_vars($data);
    $keys = array_keys($obj);

    $convertToArray = array_reduce($keys, function ($acc, $key) use ($obj) {
        if (is_object($obj[$key])) {
            $acc[] = [
                'name' => $key,
                'value' => preparation($obj[$key])
            ];
        } else {
            $acc[] = [
                'name' => $key,
                'value' => $obj[$key]
            ];
        }
        return $acc;
    }, []);
    return $convertToArray;
}
function arrToStr($arr, $depth)
{
    $sep = str_repeat('    ', $depth);
    if (!is_array($arr)) {
        return $arr;
    }
    $result = array_map(function ($node) use ($depth, $sep) {
        if (is_array($node['value'])) {
            return $sep . "    " . $node['name'] . " : " . arrToStr($node['value'], $depth + 1) . "\n";
        } else {
            return $sep . "    " . $node['name'] . " : " . $node['value'] . "\n";
        }
    }, $arr);
    return implode(addBrackets($result, $sep));
}
function addBrackets($tree, $sep)
{
    $first = 0;
    $last = count($tree) - 1;
    $tree[$first] = "{\n" . $tree[$first];
    $tree[$last] = $tree[$last] . $sep . "}";
    return $tree;
}
function stringify($arr, $depth)
{
    return arrToStr(preparation($arr), $depth);
}
function render($arr)
{
    return stylish($arr);
}
