<?php

namespace Differ\formaters\Stylish;

function correctStruktures($arr)
{
    if (! is_array($arr) || (array_key_exists('type', $arr) && $v['type'] == 'skip')) {
        return $arr;
    }
    $res = [];
    foreach ($arr as $v) {
        if (is_array($v) && array_key_exists('type', $v) && $v['type'] == 'parent') {
            $res["    " . $v['name']] = correctStruktures($v['value']);
        } else {
            $res["    " . $v['name']] = $v['value'];
        }
    }
    return $res;
}

function xDif($diff)
{
    $res = [];
    foreach ($diff as $array) {
        if (array_key_exists('type', $array) && $array['type'] == 'parent') {
            $res['    ' . $array['name']] = xDif($array['value']);
        } else {
            if (array_key_exists('status', $array) && $array['status'] == 'dontChange') {
                $res['    ' . $array['name']] = $array['value'];
            } elseif (array_key_exists('status', $array) && $array['status'] == 'removed') {
                $res['  - ' . $array['name']] = correctStruktures($array['value']);
            } elseif (array_key_exists('status', $array) && $array['status'] == 'added') {
                $res['  + ' . $array['name']] = correctStruktures($array['value']);
            } elseif (array_key_exists('status', $array) && $array['status'] == 'changed') {
                $res['  - ' . $array['name']] = correctStruktures($array['beforeValue']);
                $res['  + ' . $array['name']] = correctStruktures($array['afterValue']);
            }
        }
    }
    return $res;
}

function stylish($arr, $deep = 0)
{
    $sep = str_repeat('    ', $deep);
    $res = "{\n";
    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            $tmp = stylish($val, $deep + 1);
            $res .= $sep . $key . " : " . $tmp;
        } else {
            $res .= $sep . $key . " : " . $val . "\n";
        }
    }
    return $res . $sep . "}\n";
}
