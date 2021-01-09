<?php

namespace Differ\builder;

use function Funct\Collection\union;
use function Funct\Collection\sortBy;

function builder($objBefore, $objAfter, $path = "")
{
    $unicKey = union(array_keys(get_object_vars($objBefore)), array_keys(get_object_vars($objAfter)));
    // $unicKey = sortBy($unicKey, function ($num) {
    //     return $num;
    // });
    sort($unicKey);
    $res = array_map(function ($key) use ($objBefore, $objAfter, $path) {
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
                'name' => $key,
                'type' => 'changed',
                'path' => $path . '.' . $key,
                'valueBefore' => $objBefore->$key,
                'valueAfter' => $objAfter->$key
            ];
        }
        if (! property_exists($objAfter, $key)) {
            return [
                'name' => $key,
                'type' => 'removed',
                'path' => $path . '.' . $key,
                'value' => $objBefore->$key
            ];
        }
        if (! property_exists($objBefore, $key)) {
            return [
                'name' => $key,
                'type' => 'added',
                'path' => $path . '.' . $key,
                'value' => $objAfter->$key
            ];
        }
        // } else {
            return [
                'name' => $key,
                'type' => 'unchanged',
                'path' => $path . '.' . $key,
                'value' => $objBefore->$key
            ];
        // }
    }, $unicKey);
    return $res;
}
