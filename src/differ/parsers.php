<?php

namespace Differ\differ\Parsers;

function deepDiff($beforeTree, $afterTree, $res = [])
{
    foreach ($beforeTree as $beforeValue) {
        $findName = findSameName($beforeValue, $afterTree);
        if ($findName) {
            if (
                ($beforeValue['name'] == $findName['name']) && array_key_exists('type', $findName) &&
                $findName['type'] == 'parent'
            ) {
                $beforeValue['value'] = deepDiff($beforeValue['value'], $findName['value']);
                $res[] = $beforeValue;
            } elseif (($beforeValue['name'] == $findName['name']) && ($beforeValue['value'] == $findName['value'])) {
                $beforeValue['status'] = 'dontChange';
                $res[] = $beforeValue;
            } elseif (($beforeValue['name'] == $findName['name']) && ($beforeValue['value'] != $findName['value'])) {
                $beforeValue['status'] = 'changed';
                $beforeValue['beforeValue'] = $beforeValue['value'];
                $beforeValue['afterValue'] = $findName['value'];
                if (array_key_exists('type', $beforeValue)) {
                    $beforeValue['type'] = 'skip';
                }
                $res[] = $beforeValue;
            }
        } else {
            $beforeValue['status'] = 'removed';
            if (array_key_exists('type', $beforeValue)) {
                $beforeValue['type'] = 'skip';
            }
            $res[] = $beforeValue;
        }
    }
    foreach ($afterTree as $aftervalue) {
        $findName = findSameName($aftervalue, $beforeTree);
        if (! $findName) {
            $aftervalue['status'] = 'added';
            if (array_key_exists('type', $aftervalue)) {
                $aftervalue['type'] = 'skip';
            }
            $res[] = $aftervalue;
        }
    }
    usort($res, function ($item1, $item2) {
        if ($item1['name'] == $item2['name']) {
            return 0;
        }
        return ($item1['name'] < $item2['name']) ? -1 : 1;
    });
    return $res;
}

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

function niceView($arr, $deep = 0)
{
    $sep = str_repeat('    ', $deep);
    $res = "{\n";
    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            $tmp = niceView($val, $deep + 1);
            $res .= $sep . $key . " : " . $tmp;
        } else {
            $res .= $sep . $key . " : " . $val . "\n";
        }
    }
    return $res . $sep . "}\n";
}

function transformToArr($tree)
{
    $res = [];

    foreach ($tree as $key => $val) {
        if (is_object($val)) {
            $res[] = ['name' => $key,  'type' => 'parent', 'value' => transformToArr($val)];
        } else {
            $res[] = ['name' => $key, 'value' => boolOrNullToString($val)];
        }
    }
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

function findSameName($findArr, $dataArrs)
{
    ['name' => $findName] = $findArr;
    foreach ($dataArrs as $dataArr) {
        if ($findName == $dataArr['name']) {
            return $dataArr;
        }
    }
    return false;
}
// тут я тестирую xdebug
// $beforeFile = json_decode(file_get_contents(__DIR__ . "\..\..\before2.json"), true);
// $afterFile = json_decode(file_get_contents(__DIR__ . "\..\..\after2.json"), true);
// print_r(formatic(xDif(deepDiff((array) $beforeFile, (array) $afterFile))));
