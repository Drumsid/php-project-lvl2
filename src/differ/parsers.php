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
                $beforeValue['plain'] = 'plain';
                $res[] = $beforeValue;
            } elseif (($beforeValue['name'] == $findName['name']) && ($beforeValue['value'] != $findName['value'])) {
                $beforeValue['status'] = 'changed';
                $beforeValue['plain'] = 'plain';
                $beforeValue['beforeValue'] = $beforeValue['value'];
                $beforeValue['afterValue'] = $findName['value'];
                if (array_key_exists('type', $beforeValue)) {
                    $beforeValue['type'] = 'skip';
                }
                $res[] = $beforeValue;
            }
        } else {
            $beforeValue['status'] = 'removed';
            $beforeValue['plain'] = 'plain';
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
            $aftervalue['plain'] = 'plain';
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

function transformToArr($tree, $path = "")
{
    $res = [];

    foreach ($tree as $key => $val) {
        if (is_object($val)) {
            $res[] = [
                'name' => $key,
                'type' => 'parent',
                'path' => $path . '.' . $key,
                'value' => transformToArr($val, $path . '.' . $key)
            ];
        } else {
            $res[] = [
                'name' => $key,
                'path' => $path . '.' . $key,
                'value' => boolOrNullToString($val)
            ];
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
    if (! is_array($findArr) || ! is_array($dataArrs)) {
        return false;
    }
    if (! array_key_exists('name', $findArr)) {
        return false;
    }
    ['name' => $findName] = $findArr;
    foreach ($dataArrs as $dataArr) {
        if (is_array($dataArr) && array_key_exists('name', $dataArr) && $findName == $dataArr['name']) {
            return $dataArr;
        }
    }
    return false;
}

// тут я тестирую xdebug
// $beforeFile = json_decode(file_get_contents(__DIR__ . "\..\..\before2.json"), true);
// $afterFile = json_decode(file_get_contents(__DIR__ . "\..\..\after2.json"), true);
// print_r(formatic(xDif(deepDiff((array) $beforeFile, (array) $afterFile))));
