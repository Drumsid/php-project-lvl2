<?php

namespace Differ\differ\builder;

use function Funct\Collection\union;
use function Differ\formatters\stylish\addBrackets;

function builder($objBefore, $objAfter, $path = "")
{
    $unicKey = union(array_keys(get_object_vars($objBefore)), array_keys(get_object_vars($objAfter)));
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
        // else {
        if (
            property_exists($objBefore, $key) && property_exists($objAfter, $key)
            && ($objBefore->$key != $objAfter->$key)
        ) {
            return [
                'name' => $key,
                'type' => 'changed',
                // 'format' => 'plain',
                'path' => $path . '.' . $key,
                'valueBefore' => $objBefore->$key,
                'valueAfter' => $objAfter->$key
            ];
        }
        if (! property_exists($objAfter, $key)) {
            return [
                'name' => $key,
                'type' => 'removed',
                // 'format' => 'plain',
                'path' => $path . '.' . $key,
                'value' => $objBefore->$key
            ];
        }
        if (! property_exists($objBefore, $key)) {
            return [
                'name' => $key,
                'type' => 'added',
                // 'format' => 'plain',
                'path' => $path . '.' . $key,
                'value' => $objAfter->$key
            ];
        } else {
            return [
                'name' => $key,
                'type' => 'unchanged',
                'path' => $path . '.' . $key,
                'value' => $objBefore->$key
            ];
        }
        // }
    }, $unicKey);
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
    if (! is_object($data)){
        return $data;
    } else {
        $obj = get_object_vars($data);
    }
    $keys = array_keys($obj);
        $res = array_reduce($keys, function ($acc, $key) use ($obj) {
            if (is_object($obj[$key])) {
                $acc[] = [
                    'name' => $key,
                    // 'type' => 'return',
                    'value' => stringify($obj[$key])
                ];
            } else {
                $acc[] = [
                    'name' => $key,
                    // 'type' => 'return',
                    'value' => $obj[$key]
                ];
            }
            return $acc;
        }, []);
        return $res;
}

function testStr($arr, $sep)
{
    if (!is_array($arr)) {
        return $arr;
    }
    $res = array_map(function ($node) use ($sep) {
        if (is_array($node['value'])) {
            return $sep . "    " . $node['name'] . " : " . testStr($node['value'], $sep) . "\n";
        } else {
            return $sep . "    " . $node['name'] . " : " . $node['value'] . "\n";
        }
        
    }, $arr);
    return implode($res);
    // return implode(addBrackets($res, $sep));
}
// function transformObjectToArr($obj)
// {
//     if (is_object($obj)) {
//         $obj = get_object_vars($obj);
//     } else {
//         return $obj;
//     }
//     $keys = array_keys($obj);
//     $res = array_reduce($keys, function ($acc, $key) use ($obj) {
//         if (is_object($obj[$key])) {
//             $acc[] = [
//                 'name' => $key,
//                 'type' => 'return',
//                 'value' => transformObjectToArr($obj[$key])
//             ];
//         } else {
//             $acc[] = [
//                 'name' => $key,
//                 'type' => 'return',
//                 'value' => $obj[$key]
//             ];
//         }
//         return $acc;
//     }, []);
//     return $res;
// }

// function boolOrNullToString($data)
// {
//     if (is_null($data)) {
//         return 'null';
//     }
//     if (is_bool($data) && $data === true) {
//         return 'true';
//     }
//     if (is_bool($data) && $data === false) {
//         return 'false';
//     }
//     return $data;
// }

