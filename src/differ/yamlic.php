<?php

namespace Differ\differ\Yamlic;

use Symfony\Component\Yaml\Yaml;

// const PATH_YAML = __DIR__ . "/../"; ругается на константу почему то

$autoloadPath1 = __DIR__ . '/../../autoload.php';
$autoloadPath2 = __DIR__ . '/../../vendor/autoload.php';
if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}


// $yaml = file_get_contents(__DIR__ . '/../../after.yml');
$value1 = Yaml::parseFile(__DIR__ . '/../../after.yml', Yaml::PARSE_OBJECT_FOR_MAP); // парсит из Yaml в объект, без флага будет массив
$value2 = Yaml::parseFile(__DIR__ . '/../../before.yml', Yaml::PARSE_OBJECT_FOR_MAP); // парсит из Yaml в объект, без флага будет массив
// $value = Yaml::parse($yaml, Yaml::PARSE_OBJECT_FOR_MAP);
// $value = Yaml::parse("foo: bar", Yaml::PARSE_OBJECT_FOR_MAP);
print_r($value1);
print_r($value2);

$yaml = Yaml::dump($value1, 2, 4, Yaml::DUMP_OBJECT_AS_MAP); // пишет из объекта, без флага будет массив

// print_r($yaml);
file_put_contents(__DIR__ . '/../../test.yml', $yaml);
// foreach ($value as $val) {
//     var_dump($val);
// }

function genDiff2($json1, $json2)
{

    $compareJson1InJson2 = [];
    foreach ($json1 as $key1 => $vol1) {
        foreach ($json2 as $key2 => $vol2) {
            if (array_key_exists($key1, $json2)) {
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
    foreach ($json2 as $key2 => $vol2) {
        if (!array_key_exists("    " . $key2, $compareJson1InJson2)) {
            $searchNewDataInJson2["  + " . $key2] = " " . json_encode($vol2);
        }
    }

    $strJson = json_encode(array_merge($compareJson1InJson2, $searchNewDataInJson2));
    return $strJson;
    // $tmp = afterFistBeforLast(str_replace(',', PHP_EOL, $strJson), PHP_EOL);
    // return str_replace('"', "", $tmp);
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

// пробовал сравнить объект и записать в файл
print_r(genDiff2($value1, $value2));
$newYaml = genDiff2($value1, $value2);

$valueYaml = Yaml::parse($newYaml, Yaml::PARSE_OBJECT_FOR_MAP);
$yamlWrite = Yaml::dump($valueYaml, 2, 4, Yaml::DUMP_OBJECT_AS_MAP);
file_put_contents(__DIR__ . '/../../test2.yml', $yamlWrite);
