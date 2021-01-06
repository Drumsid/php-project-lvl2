<?php

namespace Differ\formatters\plain;

function buldPlain($tree)
{
    $res = array_reduce($tree, function ($acc, $node) {
        if (array_key_exists('type', $node) && $node['type'] == 'nested') {
            $tmp = buldPlain($node['children']);
            $acc = array_merge($acc, $tmp);
        }
        if (array_key_exists('type', $node) && $node['type'] == 'changed') {
            $node['valueBefore'] = stringify($node['valueBefore']);
            $node['valueAfter'] = stringify($node['valueAfter']);
            $acc[] = "Property '" . substr($node['path'], 1) . "' was updated. From " .
            renderNodeValue($node['valueBefore']) .  " to "  . renderNodeValue($node['valueAfter']) . ".";
        }
        if (array_key_exists('type', $node) && $node['type'] == 'removed') {
            $acc[] = "Property '" . substr($node['path'], 1) . "' was removed.";
        }
        if (array_key_exists('type', $node) && $node['type'] == 'added') {
            $node['value'] = stringify($node['value']);
            $acc[] = "Property '" . substr($node['path'], 1) . "' was added with value: " .
            renderNodeValue($node['value']) . ".";
        }
        return $acc;
    }, []);
    return $res;
}
function stringify($data)
{
    if (is_null($data)) {
        return 'null';
    }
    if (is_bool($data) && $data === true) {
        return 'true';
    }
    if (is_bool($data) && $data === false) {
        return 'false';
    }
    if (! is_object($data)) {
        return $data;
    } else {
        $obj = get_object_vars($data);
    }
    $keys = array_keys($obj);

    $res = array_reduce($keys, function ($acc, $key) use ($obj) {
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
    return $res;
}
function renderNodeValue($val)
{
    if (is_array($val)) {
        return "[complex value]";
    }
    return "'" . $val . "'";
}

function plain($arr)
{
    return implode("\n", buldPlain($arr));
}

function render($arr)
{
    return plain($arr);
}
