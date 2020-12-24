<?php

namespace Differ\formatters\plain;

use function Differ\differ\builder\transformObjectToArr;
use function Differ\differ\builder\boolOrNullToString;

function buldPlain($tree)
{
    $res = array_reduce($tree, function ($acc, $node) {
        if (/*array_key_exists('type', $node) && */$node['type'] == 'nested') {
            $children = buldPlain($node['children']);
            $acc = array_merge($acc, $children);
        }
        if (/*array_key_exists('format', $node) && */$node['type'] == 'changed') {
            $node['valueBefore'] = transformObjectToArr(boolOrNullToString($node['valueBefore']));
            $node['valueAfter'] = transformObjectToArr(boolOrNullToString($node['valueAfter']));
            $acc[] = "Property '" . substr($node['path'], 1) . "' was updated. From " .
            renderNodeValue($node['valueBefore']) .  " to "  . renderNodeValue($node['valueAfter']) . ".";
        }
        if (/*array_key_exists('format', $node) && */$node['type'] == 'removed') {
            $acc[] = "Property '" . substr($node['path'], 1) . "' was removed.";
        }
        if (/*array_key_exists('format', $node) && */$node['type'] == 'added') {
            $node['value'] = transformObjectToArr(boolOrNullToString($node['value']));
            $acc[] = "Property '" . substr($node['path'], 1) . "' was added with value: " .
            renderNodeValue($node['value']) . ".";
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
