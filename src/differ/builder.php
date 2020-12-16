<?php

namespace Differ\differ\builder;

use function Funct\Collection\union;

function builder($objBefore, $objAfter, $path = "")
{
    $unicKey = union(array_keys(get_object_vars($objBefore)), array_keys(get_object_vars($objAfter)));

    $res = array_map(function ($key) use ($objBefore, $objAfter, $path) {
        if (
            property_exists($objBefore, $key) && property_exists($objAfter, $key)
            && is_object($objBefore->$key) && is_object($objAfter->$key)
        ) {
            return [
                'name' => $key,
                'status' => 'nested',
                'path' => $path . '.' . $key,
                'value' => builder($objBefore->$key, $objAfter->$key, $path . '.' . $key)
            ];
        }
        if (
            property_exists($objBefore, $key) && property_exists($objAfter, $key)
            && ($objBefore->$key == $objAfter->$key)
        ) {
            return [
                'name' => $key,
                'status' => 'unchanged',
                'plain' => 'plain',
                'path' => $path . '.' . $key,
                'value' => boolOrNullToString($objBefore->$key)
            ];
        }
        if (
            property_exists($objBefore, $key) && property_exists($objAfter, $key)
            && ($objBefore->$key != $objAfter->$key)
        ) {
            return [
                'name' => $key,
                'status' => 'changed',
                'plain' => 'plain',
                'path' => $path . '.' . $key,
                'valueBefore' => transformObjectToArr(boolOrNullToString($objBefore->$key)),
                'valueAfter' => transformObjectToArr(boolOrNullToString($objAfter->$key))
            ];
        }
        if (property_exists($objBefore, $key) && ! property_exists($objAfter, $key)) {
            return [
                'name' => $key,
                'status' => 'removed',
                'plain' => 'plain',
                'path' => $path . '.' . $key,
                'value' => transformObjectToArr(boolOrNullToString($objBefore->$key))
            ];
        }
        if (! property_exists($objBefore, $key) && property_exists($objAfter, $key)) {
            return [
                'name' => $key,
                'status' => 'added',
                'plain' => 'plain',
                'path' => $path . '.' . $key,
                'value' => transformObjectToArr(boolOrNullToString($objAfter->$key))
            ];
        }
    }, $unicKey);

    usort($res, function ($item1, $item2) {
        if ($item1['name'] == $item2['name']) {
            return 0;
        }
        return ($item1['name'] < $item2['name']) ? -1 : 1;
    });
    return $res;
}

function transformObjectToArr($obj)
{
    if (is_object($obj)) {
        $obj = get_object_vars($obj);
    } else {
        return $obj;
    }
    $keys = array_keys($obj);
    $res = array_reduce($keys, function ($acc, $key) use ($obj) {
        if (is_object($obj[$key])) {
            $acc[] = [
                'name' => $key,
                'status' => 'return',
                'value' => transformObjectToArr($obj[$key])
            ];
        } else {
            $acc[] = [
                'name' => $key,
                'status' => 'return',
                'value' => $obj[$key]
            ];
        }
        return $acc;
    }, []);
    return $res;
}

function boolOrNullToString($data)
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
    return $data;
}
