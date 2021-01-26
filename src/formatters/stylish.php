<?php

namespace Differ\formatters\stylish;

function stylish(array $tree, int $depth = 0): string
{
    $indent = str_repeat('    ', $depth);
    $stylishData = array_map(function ($node) use ($indent, $depth): string {
        $type = $node['type'];
        $name = $node['name'];
        $result = "";
        switch ($type) {
            case 'nested':
                $children = stylish($node['children'], $depth + 1);
                // $result = "{$indent}    {$name}: {$children}";
                return "{$indent}    {$name}: {$children}";
                // break;
            case 'unchanged':
                $unchanged = stringify($node['value'], $depth + 1);
                // $result = "{$indent}    {$name}: {$unchanged}";
                return "{$indent}    {$name}: {$unchanged}";
                // break;
            case 'changed':
                $changedBefore = stringify($node['valueBefore'], $depth + 1);
                $changedAfter = stringify($node['valueAfter'], $depth + 1);
                // $result = "{$indent}  - {$name}: {$changedBefore}\n{$indent}  + {$name}: {$changedAfter}";
                return "{$indent}  - {$name}: {$changedBefore}\n{$indent}  + {$name}: {$changedAfter}";
                // break;
            case 'removed':
                $removed = stringify($node['value'], $depth + 1);
                // $result = "{$indent}  - {$name}: {$removed}";
                return "{$indent}  - {$name}: {$removed}";
                // break;
            case 'added':
                $added = stringify($node['value'], $depth + 1);
                // $result = "{$indent}  + {$name}: {$added}";
                return "{$indent}  + {$name}: {$added}";
                // break;
        }
        return $result;
    }, $tree);
        return implode("\n", addBrackets($stylishData, $indent));
}
function stringify($data, int $depth): string
{
    if (is_null($data)) {
        return 'null';
    }
    if (is_bool($data)) {
        return $data ? 'true' : 'false';
    }
    if (is_object($data)) {
        $obj = get_object_vars($data);
        $keys = array_keys($obj);
        $dataFromObject = array_map(function ($key) use ($obj, $depth): array {
            if (is_object($obj[$key])) {
                return [
                    'name' => $key,
                    'value' => stringify($obj[$key], $depth + 1)
                ];
            } else {
                return [
                    'name' => $key,
                    'value' => $obj[$key]
                ];
            }
        }, $keys);
        return arrToStr($dataFromObject, $depth);
    }
    return $data;
}
function arrToStr(array $arr, int $depth): string
{
    $indent = str_repeat('    ', $depth);
    $result = array_map(function ($node) use ($depth, $indent): string {
        if (is_array($node['value'])) {
            $children = arrToStr($node['value'], $depth + 1);
            return "{$indent}    {$node['name']}: {$children}";
        } else {
            return "{$indent}    {$node['name']}: {$node['value']}";
        }
    }, $arr);
    return implode("\n", addBrackets($result, $indent));
}
// function addBrackets(array $tree, string $indent): array
// {
//     $first = 0;
//     $last = count($tree) - 1;
//     $tree[$first] = "{\n{$tree[$first]}";
//     $tree[$last] = "{$tree[$last]}\n{$indent}}";
//     print_r($tree);
//     return $tree;
// }

function addBrackets(array $tree, string $indent): array
{
    $last = count($tree) - 1;
    $keys = array_keys($tree);
    $result = array_map(function ($item, $key) use ($last, $indent) {
        if ($last == 0) {
             return "{\n{$item}\n{$indent}}";
        }
        if ($key === 0) {
            return "{\n{$item}";
        }
        if ($key === $last) {
            return "{$item}\n{$indent}}";
        }
        return $item;
    }, $tree, $keys);
    return $result;
}
function render(array $arr): string
{
    return stylish($arr);
}
