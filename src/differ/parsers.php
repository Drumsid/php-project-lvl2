<?php

namespace Differ\differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function deepDiff($beforeTree, $afterTree)
{
    $comparedData = array_reduce($beforeTree, function ($acc, $before) use ($afterTree) {
        $after = findSameName($before, $afterTree);
        if ($after) {
            if (
                ($before['name'] == $after['name']) && array_key_exists('type', $after) &&
                $after['type'] == 'parent'
            ) {
                $before['value'] = deepDiff($before['value'], $after['value']);
                $acc[] = $before;
            } elseif (($before['name'] == $after['name']) && ($before['value'] == $after['value'])) {
                $before['status'] = 'dontChange';
                $before['plain'] = 'plain';
                $acc[] = $before;
            } elseif (($before['name'] == $after['name']) && ($before['value'] != $after['value'])) {
                $before['status'] = 'changed';
                $before['plain'] = 'plain';
                $before['beforeValue'] = $before['value'];
                $before['afterValue'] = $after['value'];
                if (array_key_exists('type', $before)) {
                    $before['type'] = 'skip';
                }
                $acc[] = $before;
            }
        } else {
            $before['status'] = 'removed';
            $before['plain'] = 'plain';
            if (array_key_exists('type', $before)) {
                $before['type'] = 'skip';
            }
            $acc[] = $before;
        }
        return $acc;
    }, []);

    $result = array_reduce($afterTree, function ($acc, $after) use ($beforeTree) {
        $find = findSameName($after, $beforeTree);
        if (! $find) {
            $after['status'] = 'added';
            $after['plain'] = 'plain';
            if (array_key_exists('type', $after)) {
                $after['type'] = 'skip';
            }
            $acc[] = $after;
        }
        return $acc;
    }, $comparedData);

    usort($result, function ($item1, $item2) {
        if ($item1['name'] == $item2['name']) {
            return 0;
        }
        return ($item1['name'] < $item2['name']) ? -1 : 1;
    });

    return $result;
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

function checkExpansion($file)
{

    if (substr($file, -4) == ".yml" && file_exists($file)) {
        return Yaml::parseFile($file, Yaml::PARSE_OBJECT_FOR_MAP);
    }
    if (substr($file, -5) == ".json" && file_exists($file)) {
        return json_decode(file_get_contents($file));
    }
    exit("{$file} file not exists or path incorrect\n");
}
