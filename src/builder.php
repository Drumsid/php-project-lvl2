<?php

namespace Differ\Builder;

use function Funct\Collection\union;
use function Funct\Collection\sortBy;

function builder(object $objBefore, object $objAfter): array
{
    $unicKey = union(array_keys(get_object_vars($objBefore)), array_keys(get_object_vars($objAfter)));
    $sortedUnicKey = array_values(sortBy($unicKey, function ($key) {
        return $key;
    }));
    $tree = array_map(function ($key) use ($objBefore, $objAfter) {
        if (! property_exists($objAfter, $key)) {
            return [
                'name' => $key,
                'type' => 'removed',
                'value' => $objBefore->$key
            ];
        }
        if (! property_exists($objBefore, $key)) {
            return [
                'name' => $key,
                'type' => 'added',
                'value' => $objAfter->$key
            ];
        }
        if (is_object($objBefore->$key) && is_object($objAfter->$key)) {
            return [
                'name' => $key,
                'type' => 'nested',
                'children' => builder($objBefore->$key, $objAfter->$key)
            ];
        }
        if ($objBefore->$key !== $objAfter->$key) {
            return [
                'name' => $key,
                'type' => 'changed',
                'valueBefore' => $objBefore->$key,
                'valueAfter' => $objAfter->$key
            ];
        }
            return [
                'name' => $key,
                'type' => 'unchanged',
                'value' => $objBefore->$key
            ];
    }, $sortedUnicKey);
    return $tree;
}
