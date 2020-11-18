<?php

namespace Differ\differ\Parsers;

// $autoloadPath1 = __DIR__ . '\..\..\..\autoload.php';
// $autoloadPath2 = __DIR__ . '\..\..\vendor\autoload.php';
// if (file_exists($autoloadPath1)) {
//     require_once $autoloadPath1;
// } else {
//     require_once $autoloadPath2;
// }

function parsing($beforeFile, $afterFile) // old function
{
    $compareBeforeInAfter = [];
    foreach ($beforeFile as $keyBefore => $volBefore) {
        foreach ($afterFile as $keyAfter => $volAfter) {
            if (array_key_exists($keyBefore, $afterFile)) {
                if ($keyBefore == $keyAfter && $volBefore == $volAfter) {
                    $compareBeforeInAfter["    " . $keyBefore] = " " . $volBefore;
                }
                if ($keyBefore == $keyAfter && $volBefore != $volAfter) {
                    $compareBeforeInAfter["  + " . $keyAfter] = " " . $volAfter;
                    $compareBeforeInAfter["  - " . $keyBefore] = " " . $volBefore;
                }
            } else {
                $compareBeforeInAfter["  - " . $keyBefore] = " " . $volBefore;
            }
        }
    }

    $searchNewDataInAfter = [];
    foreach ($afterFile as $keyAfter => $volAfter) {
        if (!array_key_exists("    " . $keyAfter, $compareBeforeInAfter)) {
            $searchNewDataInAfter["  + " . $keyAfter] =
            $volAfter == is_bool($volAfter) ? " " . json_encode($volAfter) : " " . $volAfter;
        }
    }

    $strJson = json_encode(array_merge($compareBeforeInAfter, $searchNewDataInAfter));
    return $strJson;
}

function correctCurleBrackets($str, $delimiter) // old function
{
    $search = "";
    for ($i = 0; $i < strlen($str); $i++) {
        $search .= $str[$i];
        if ($i == 0) {
            $search .= $delimiter;
        }
        if ($i == strlen($str) - 2) {
            $search .= $delimiter;
        }
    }
    return $search;
}

function deepDiff($arrBefore, $arrAfter, $acc = [])
{
    foreach ($arrBefore as $keyBefore => $valBefore) {
        foreach ($arrAfter as $keyAfter => $valAfter) {
            if (array_key_exists($keyBefore, $arrAfter)) {
              // равны ли ключи
                if ($keyBefore == $keyAfter && is_array($valBefore) && is_array($valAfter)) {
                    $acc[$keyBefore] = deepDiff($valBefore, $valAfter);
                } elseif ($keyBefore == $keyAfter && $valBefore == $valAfter) {
                    $acc[$keyBefore] = ['value' => $valBefore, 'status' => 'dontChange'];
                    break;
                } elseif ($keyBefore == $keyAfter && $valBefore != $valAfter) {
                    $valAfter = boolOrNullToString($valAfter);
                    $valBefore = boolOrNullToString($valBefore);
                    $acc[$keyBefore] = ['beforeValue' => $valBefore, 'afterValue' => $valAfter, 'skip' => true];
                    break;
                }
            } else {
                $valBefore = boolOrNullToString($valBefore);
                $acc[$keyBefore] = ['value' => $valBefore, 'status' => 'removed', 'skip' => true];
                break;
            }
        }
    }
    foreach ($arrAfter as $keyAfter => $valAfter) {
        if (! array_key_exists($keyAfter, $arrBefore)) {
            // $valAfter = is_bool($valAfter) || is_null($valAfter) ? boolOrNullToString($valAfter) : $valAfter;
            $valAfter =  boolOrNullToString($valAfter);
            $acc[$keyAfter] = ['value' => $valAfter, 'status' => 'added', 'skip' => true];
        }
    }
    ksort($acc);
    return $acc;
    // return sortArr($acc);
}

function xDif($diff)
{
    $res = [];
    foreach ($diff as $key => $array) {
        if (is_array($array) && is_array(reset($array)) && ! array_key_exists('skip', $array)) {
            $res[$key] = xDif($array);
        } else {
            if (array_key_exists('status', $array) && $array['status'] == 'dontChange') {
                $res['    ' . $key] = $array['value'];
            } elseif (array_key_exists('status', $array) && $array['status'] == 'removed') {
                $res['  - ' . $key] = $array['value'];
            } elseif (array_key_exists('status', $array) && $array['status'] == 'added') {
                $res['  + ' . $key] = $array['value'];
            } elseif (array_key_exists('beforeValue', $array) && array_key_exists('afterValue', $array)) {
                $res['  - ' . $key] = $array['beforeValue'];
                $res['  + ' . $key] = $array['afterValue'];
            }
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

function formatic($arr) // без глобал не работает, не пойму почему
{
    $deep = 0;

    return niceView($arr);
}
function niceView($arr, $deep = 0) // unit test ругался на то что эту функцию обьявил внутри другой
{
    global $deep;
    $sep = str_repeat('    ', $deep);
    $res = "{\n";
    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            $tmp = niceView($val, $deep += 1);
            $res .= $sep . $key . " : " . $tmp;
        } else {
            $res .= $sep . $key . " : " . $val . "\n";
        }
    }
    if ($deep > 1) {
        $deep = 0;
        return $res . $sep . "}\n";
    }
    return $res . $sep . "}\n";
}
// тут я тестирую xdebug
// $beforeFile = json_decode(file_get_contents(__DIR__ . "\..\..\before2.json"), true);
// $afterFile = json_decode(file_get_contents(__DIR__ . "\..\..\after2.json"), true);
// print_r(formatic(xDif(deepDiff((array) $beforeFile, (array) $afterFile))));
