<?php

namespace Differ\builder;

use function Funct\Collection\union;
use function Funct\Collection\sortBy;

function builder($objBefore, $objAfter, $path = "")
{
    $unicKey = union(array_keys(get_object_vars($objBefore)), array_keys(get_object_vars($objAfter)));
    $sortedUnicKey = array_values(sortBy($unicKey, function ($key) {
        return $key;
    }));
    $tree = array_map(function ($key) use ($objBefore, $objAfter, $path) {
        if (
            property_exists($objBefore, $key) && property_exists($objAfter, $key)
            && is_object($objBefore->$key) && is_object($objAfter->$key)
        ) {
            return [
                'name' => $key,
                'type' => 'nested',
                'children' => builder($objBefore->$key, $objAfter->$key, $path . '.' . $key)
            ];
        }
        if (
            property_exists($objBefore, $key) && property_exists($objAfter, $key)
            && ($objBefore->$key != $objAfter->$key)
        ) {
            return [
                'name' => $path . '.' . $key,
                'type' => 'changed',
                'valueBefore' => $objBefore->$key,
                'valueAfter' => $objAfter->$key
            ];
        }
        if (! property_exists($objAfter, $key)) {
            return [
                'name' => $path . '.' . $key,
                'type' => 'removed',
                'value' => $objBefore->$key
            ];
        }
        if (! property_exists($objBefore, $key)) {
            return [
                'name' => $path . '.' . $key,
                'type' => 'added',
                'value' => $objAfter->$key
            ];
        }
            return [
                'name' => $path . '.' . $key,
                'type' => 'unchanged',
                'value' => $objBefore->$key
            ];
    }, $sortedUnicKey);
    return $tree;
}
