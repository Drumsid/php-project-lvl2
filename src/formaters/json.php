<?php

namespace Differ\formaters\json;

function jsonFormat($tree)
{
    $res = array_map(function ($node) {
        if ($node['type'] == 'nested') {
            return [
                    'name' => $node['name'],
                    'type' => $node['type'],
                    'value' => jsonFormat($node['value']),
                ];
        } else {
            if (array_key_exists('valueBefore', $node)) {
                return [
                    'name' => $node['name'],
                    'type' => $node['type'],
                    'valueBefore' => $node['valueBefore'],
                    'valueAfter' => $node['valueAfter'],
                ];
            } else {
                return [
                    'name' => $node['name'],
                    'type' => $node['type'],
                    'value' => $node['value'],
                ];
            }
        }
    }, $tree);

    return $res;
}
