<?php

namespace Differ\differ\genDiff;

const CORRECT_PATH = __DIR__ . "/../";

function afterFistBeforLast($str, $del)
{
    $search = "";
    for ($i = 0; $i < strlen($str); $i++) {
        $search .= $str[$i];
        if ($i == 0) {
            $search .= $del;
        }
        if ($i == strlen($str) - 2) {
            $search .= $del;
        }
    }
    return $search;
}

function correct_path($path)
{
    if (file_exists($path)) {
        return json_decode(file_get_contents($path), true);
    }
    if (file_exists($path)) {
        return json_decode(file_get_contents(CORRECT_PATH . $path), true);
    }
    return $path;
}

function genDiff($beforeJson, $afterJson)
{
    $beforeJson = correct_path($beforeJson);
    $afterJson = correct_path($afterJson);

    if (! is_array($beforeJson)) {
        return "{$beforeJson} file not exists or path incorrect\n";
    }
    if (! is_array($beforeJson)) {
        return "{$afterJson} file not exists or path incorrect\n";
    }

    $compareJson1InJson2 = [];
    foreach ($beforeJson as $key1 => $vol1) {
        foreach ($afterJson as $key2 => $vol2) {
            if (array_key_exists($key1, $afterJson)) {
                if ($key1 == $key2 && $vol1 == $vol2) {
                    $compareJson1InJson2["    " . $key1] = " " . $vol1;
                }
                if ($key1 == $key2 && $vol1 != $vol2) {
                    $compareJson1InJson2["  + " . $key2] = " " . $vol2;
                    $compareJson1InJson2["  - " . $key1] = " " . $vol1;
                }
            } else {
                $compareJson1InJson2["  - " . $key1] = " " . $vol1;
            }
        }
    }

    $searchNewDataInJson2 = [];
    foreach ($afterJson as $key2 => $vol2) {
        if (!array_key_exists("    " . $key2, $compareJson1InJson2)) {
            $searchNewDataInJson2["  + " . $key2] = " " . $vol2 = is_bool($vol2) ? json_encode($vol2) : $vol2;
        }
    }

    $strJson = json_encode(array_merge($compareJson1InJson2, $searchNewDataInJson2));

    $tmp = afterFistBeforLast(str_replace(',', PHP_EOL, $strJson), PHP_EOL);
    return str_replace('"', "", $tmp);
}
