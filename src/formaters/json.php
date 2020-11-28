<?php

namespace Differ\formaters\Json;

function niceJsonView($arr, $deep = 0)
{
    $sep = str_repeat('    ', $deep);
    $res = "{\n";
    $last = count($arr) - 1;
    $count = 0;
    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            $tmp = niceJsonView($val, $deep + 1);
            $res .= $sep . "\"" . $key . "\" : " . $tmp;
            $count++;
        } else {
            if ($count == $last) {
                $res .= $sep . "\"" . $key . "\" : " . "\"" . $val . "\"\n";
            } else {
                $res .= $sep . "\"" . $key . "\" : " . "\"" . $val . "\",\n";
                $count++;
            }
        }
    }
    return $res . $sep . "}\n";
}
