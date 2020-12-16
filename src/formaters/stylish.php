<?php

namespace Differ\formaters\stylish;

const UNCHANGED = "    ";
const PLUS = "  + ";
const MINUS = "  - ";

function stylish($arr, $deep = 0)
{
    $sep = str_repeat('    ', $deep);
    $res = array_map(function ($item) use ($sep, $deep) {
        if ($item['status'] == 'nested') {
            $tmp = stylish($item['value'], $deep + 1);
            return $sep . UNCHANGED . $item['name'] . " : " . $tmp . "\n";
        }
        if ($item['status'] == 'unchanged') {
            $tmp = arrToStr($item['value'], $deep + 1);
            return $sep . UNCHANGED . $item['name'] . " : " . $tmp . "\n";
        }
        if ($item['status'] == 'changed') {
            $tempBefore = arrToStr($item['valueBefore'], $deep + 1);
            $tempAfter = arrToStr($item['valueAfter'], $deep + 1);
            return $sep . MINUS . $item['name'] . " : " . $tempBefore . "\n" . $sep .
            PLUS . $item['name'] . " : " . $tempAfter . "\n";
        }
        if ($item['status'] == 'removed') {
            $tmp = arrToStr($item['value'], $deep + 1);
            return $sep . MINUS . $item['name'] . " : " . $tmp . "\n";
        }
        if ($item['status'] == 'added') {
            $tmp = arrToStr($item['value'], $deep + 1);
            return $sep . PLUS . $item['name'] . " : " . $tmp . "\n";
        }
        if ($item['status'] == 'return') {
            $tmp = arrToStr($item['value'], $deep + 1);
            return $sep . UNCHANGED . $item['name'] . " : " . $tmp . "\n";
        }
    }, $arr);
        array_unshift($res, "{\n");
        array_push($res, $sep . "}");
    return implode($res);
}

function arrToStr($arr, $deep)
{
    if (is_array($arr)) {
        return stylish($arr, $deep);
    } else {
        return $arr;
    }
}
