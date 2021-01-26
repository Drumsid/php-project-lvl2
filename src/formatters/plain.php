<?php

namespace Differ\Formatters\Plain;

function buldPlain(array $tree, string $path = ""): array
{
    $plainData = array_reduce($tree, function ($acc, $node) use ($path) {
        $type = $node['type'];
        $fullPath = "{$path}{$node['name']}";
        switch ($type) {
            case 'nested':
                $children = buldPlain($node['children'], "{$fullPath}.");
                return array_merge($acc, $children);
            case 'changed':
                $valueBefore = stringify($node['valueBefore']);
                $valueAfter = stringify($node['valueAfter']);
                return [...$acc, "Property '{$fullPath}' was updated. From {$valueBefore} to {$valueAfter}"];
            case 'removed':
                return [...$acc, "Property '{$fullPath}' was removed"];
            case 'added':
                $value = stringify($node['value']);
                return [...$acc, "Property '{$fullPath}' was added with value: {$value}"];
        }
        return $acc;
    }, []);
    return $plainData;
}
function stringify($data): string
{
    if (is_null($data)) {
        return 'null';
    }
    if (is_bool($data)) {
        return $data ? 'true' : 'false';
    }
    if (is_object($data) || is_array($data)) {
        return "[complex value]";
    }
    return is_numeric($data) ? (string) $data : "'$data'";
}
function render(array $arr): string
{
    return implode("\n", buldPlain($arr));
}
