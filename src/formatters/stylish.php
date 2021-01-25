<?php

namespace Differ\formatters\stylish;

function stylish(array $tree, int $depth = 0): string
{
    $indent = str_repeat('    ', $depth);
    $stylishData = array_map(function ($node) use ($indent, $depth) {
        $type = $node['type'];
        $name = $node['name'];
        switch ($type) {
            case 'nested':
                $children = stylish($node['children'], $depth + 1);
                return "{$indent}    {$name}: {$children}";
            case 'unchanged':
                $unchanged = stringify($node['value'], $depth + 1);
                return "{$indent}    {$name}: {$unchanged}";
            case 'changed':
                $changedBefore = stringify($node['valueBefore'], $depth + 1);
                $changedAfter = stringify($node['valueAfter'], $depth + 1);
                return "{$indent}  - {$name}: {$changedBefore}\n{$indent}  + {$name}: {$changedAfter}";
            case 'removed':
                $removed = stringify($node['value'], $depth + 1);
                return "{$indent}  - {$name}: {$removed}";
            case 'added':
                $added = stringify($node['value'], $depth + 1);
                return "{$indent}  + {$name}: {$added}";
        }
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
function addBrackets(array $tree, string $indent): array
{
    $first = 0;
    $last = count($tree) - 1;
    $tree[$first] = "{\n{$tree[$first]}";
    $tree[$last] = "{$tree[$last]}\n{$indent}}";
    return $tree;
}
function render(array $arr): string
{
    return stylish($arr);
}
