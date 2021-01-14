<?php

namespace Differ\formatters\plain;

function buldPlain($tree)
{
    $plainData = array_reduce($tree, function ($acc, $node) {
        $type = $node['type'];
        $path = substr($node['name'], 1);
        switch ($type) {
            case 'nested':
                $tmp = buldPlain($node['children']);
                $acc = array_merge($acc, $tmp);
                break;
            case 'changed':
                $valueBefore = stringify($node['valueBefore']);
                $valueAfter = stringify($node['valueAfter']);
                $acc[] = "Property '{$path}' was updated. From {$valueBefore} to {$valueAfter}.";
                break;
            case 'removed':
                $acc[] = "Property '{$path}' was removed.";
                break;
            case 'added':
                $value = stringify($node['value']);
                $acc[] = "Property '{$path}' was added with value: {$value}.";
                break;
        }
        return $acc;
    }, []);
    return $plainData;
}
function stringify($data)
{
    if (is_null($data)) {
        return "'null'";
    }
    if (is_bool($data)) {
        return ($data === true) ? "'true'" : "'false'";
    }
    if (! is_object($data)) {
        return "'$data'";
    }
    $obj = get_object_vars($data);
    $keys = array_keys($obj);

    $complexValue = array_reduce($keys, function ($acc, $key) use ($obj) {
        if (is_object($obj[$key])) {
            $acc[] = [
                'name' => $key,
                'value' => stringify($obj[$key])
            ];
        } else {
            $acc[] = [
                'name' => $key,
                'value' => $obj[$key]
            ];
        }
        return $acc;
    }, []);

    if (is_array($complexValue)) {
        return "[complex value]";
    }
    return  "'$complexValue'";
}

function render($arr)
{
    return implode("\n", buldPlain($arr));
}
