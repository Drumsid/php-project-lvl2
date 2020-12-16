<?php

namespace Differ\formaters\plain;

function buldPlain($tree)
{
    $res = array_reduce($tree, function ($acc, $node) {
        if (array_key_exists('status', $node) && $node['status'] == 'nested') {
            $tmp = buldPlain($node['value']);
            $acc = array_merge($acc, $tmp);
        }
        if (array_key_exists('plain', $node) && $node['status'] == 'changed') {
            $acc[] = "Property '" . substr($node['path'], 1) . "' was updated. From " .
            checkArray($node['valueBefore']) .  " to "  . checkArray($node['valueAfter']) . ".";
        }
        if (array_key_exists('plain', $node) && $node['status'] == 'removed') {
            $acc[] = "Property '" . substr($node['path'], 1) . "' was removed.";
        }
        if (array_key_exists('plain', $node) && $node['status'] == 'added') {
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
