<?php

namespace Differ\formaters\Plain;

function collectPathArr($arr)
{
    $res = [];
    foreach ($arr as $key => $value) {
        if (array_key_exists('type', $value) && $value['type'] == 'parent') {
            $tmp = collectPathArr($value['value']);
            $res = array_merge($res, $tmp);
        } else {
            if (array_key_exists('plain', $value) && $value['status'] == 'changed') {
                $res[] = "Property '" . substr($value['path'], 1) . "' was updated. From " .
                checkArray($value['beforeValue']) .  " to "  . checkArray($value['afterValue']) . ".";
            }
            if (array_key_exists('plain', $value) && $value['status'] == 'removed') {
                $res[] = "Property '" . substr($value['path'], 1) . "' was removed.";
            }
            if (array_key_exists('plain', $value) && $value['status'] == 'added') {
                $res[] = "Property '" . substr($value['path'], 1) . "' was added with value: " .
                checkArray($value['value']) . ".";
            }
        }
    }
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
    return implode("\n", collectPathArr($arr));
}
