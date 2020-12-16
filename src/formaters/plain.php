<?php

namespace Differ\formaters\plain;

function buldPlain($tree)
{
    $res = array_reduce($tree, function ($acc, $node) {
        if (array_key_exists('type', $node) && $node['type'] == 'nested') {
            $tmp = buldPlain($node['value']);
            $acc = array_merge($acc, $tmp);
        }
        if (array_key_exists('format', $node) && $node['type'] == 'changed') {
            $acc[] = "Property '" . substr($node['path'], 1) . "' was updated. From " .
            checkArray($node['valueBefore']) .  " to "  . checkArray($node['valueAfter']) . ".";
        }
        if (array_key_exists('format', $node) && $node['type'] == 'removed') {
            $acc[] = "Property '" . substr($node['path'], 1) . "' was removed.";
        }
        if (array_key_exists('format', $node) && $node['type'] == 'added') {
            $acc[] = "Property '" . substr($node['path'], 1) . "' was added with value: " .
            checkArray($node['value']) . ".";
        }
        return $acc;
    }, []);
    return $res;
}

function checkArray($val)
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
