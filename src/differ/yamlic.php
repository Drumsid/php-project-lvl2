<?php

namespace Differ\differ\Yamlic;

use Symfony\Component\Yaml\Yaml;

const CORRECT_PATH_YAML = __DIR__ . "/../../";

// $autoloadPath1 = __DIR__ . '/../../autoload.php';    // без этой записи в bin/gendiff функция parseYml работает
// $autoloadPath2 = __DIR__ . '/../../vendor/autoload.php';  // но если без этой записи вызвать
// if (file_exists($autoloadPath1)) {                       // в этом файле функцию parseYml - ошибка
//     require_once $autoloadPath1;
// } else {
//     require_once $autoloadPath2;
// }


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

function parseYml($yaml1, $yaml2)
{
    $yaml1 = Yaml::parseFile(CORRECT_PATH_YAML . $yaml1, Yaml::PARSE_OBJECT_FOR_MAP);
    $yaml2 = Yaml::parseFile(CORRECT_PATH_YAML . $yaml2, Yaml::PARSE_OBJECT_FOR_MAP);


    $compareYml1InYml2 = [];
    foreach ($yaml1 as $key1 => $vol1) {
        foreach ($yaml2 as $key2 => $vol2) {
            if (array_key_exists($key1, $yaml2)) {
                if ($key1 == $key2 && $vol1 == $vol2) {
                    $compareYml1InYml2["    " . $key1] = " " . $vol1;
                }
                if ($key1 == $key2 && $vol1 != $vol2) {
                    $compareYml1InYml2["  + " . $key2] = " " . $vol2;
                    $compareYml1InYml2["  - " . $key1] = " " . $vol1;
                }
            } else {
                $compareYml1InYml2["  - " . $key1] = " " . $vol1;
            }
        }
    }

    $searchNewDataInYml2 = [];
    foreach ($yaml2 as $key2 => $vol2) {
        if (!array_key_exists("    " . $key2, $compareYml1InYml2)) {
            $searchNewDataInYml2["  + " . $key2] = " " . json_encode($vol2);
        }
    }

    $strJson = json_encode(array_merge($compareYml1InYml2, $searchNewDataInYml2));

    $tmp = afterFistBeforLast(str_replace(',', PHP_EOL, $strJson), PHP_EOL);
    return str_replace('"', "", $tmp);
}

// print_r(parseYml($yaml1, $yaml2));
