<?php

namespace Differ\formatters\stylish;

function stylish($tree, $depth = 0)
{
    $indent = str_repeat('    ', $depth);
    $stylishData = array_map(function ($node) use ($indent, $depth) {
        $type = $node['type'];
        $name = $node['name'];
        switch ($type) {
            case 'nested':
                $children = stylish($node['children'], $depth + 1);
                return "{$indent}    {$name}: {$children}\n";
            case 'unchanged':
                $unchanged = $node['value'];
                return "{$indent}    {$name}: {$unchanged}\n";
            case 'changed':
                $changedBefore = stringify($node['valueBefore'], $depth + 1);
                $changedAfter = stringify($node['valueAfter'], $depth + 1);
                return "{$indent}  - {$name}: {$changedBefore}\n{$indent}  + {$name}: {$changedAfter}\n";
            case 'removed':
                $removed = stringify($node['value'], $depth + 1);
                return "{$indent}  - {$name}: {$removed}\n";
            case 'added':
                $added = stringify($node['value'], $depth + 1);
                return "{$indent}  + {$name}: {$added}\n";
        }
    }, $tree);
    if (is_array($stylishData)) {
        return implode(addBrackets($stylishData, $indent));
    }
    return $stylishData;
}
function stringify($data, $depth)
{
    if (is_null($data)) {
        return 'null';
    }
    if (is_bool($data)) {
        return $data ? 'true' : 'false';
    }
    if (is_object($data)) {
        print_r($data);
        $obj = get_object_vars($data);
        $keys = array_keys($obj);

        $dataFromObject = array_reduce($keys, function ($acc, $key) use ($obj, $depth) {
            if (is_object($obj[$key])) {
                $acc[] = [
                    'name' => $key,
                    'value' => stringify($obj[$key], $depth + 1)
                ];
            } else {
                $acc[] = [
                    'name' => $key,
                    'value' => $obj[$key]
                ];
            }
            return $acc;
        }, []);
        return arrToStr($dataFromObject, $depth);
    }
    return $data;
}
function arrToStr($arr, $depth)
{
    $indent = str_repeat('    ', $depth);
    if (!is_array($arr)) {
        return $arr;
    }
    $result = array_map(function ($node) use ($depth, $indent) {
        if (is_array($node['value'])) {
            $children = arrToStr($node['value'], $depth + 1);
            return "{$indent}    {$node['name']}: {$children}\n";
        } else {
            return "{$indent}    {$node['name']}: {$node['value']}\n";
        }
    }, $arr);
    return implode(addBrackets($result, $indent));
}
function addBrackets($tree, $indent)
{
    $first = 0;
    $last = count($tree) - 1;
    $tree[$first] = "{\n" . $tree[$first];
    $tree[$last] = $tree[$last] . $indent . "}";
    return $tree;
}
function render($arr)
{
    return stylish($arr);
}
