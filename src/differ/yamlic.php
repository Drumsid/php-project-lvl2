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


// парсит из Yaml в объект, без флага будет массив
$afterYml = Yaml::parseFile(__DIR__ . '/../../after.yml', Yaml::PARSE_OBJECT_FOR_MAP);
// парсит из Yaml в объект, без флага будет массив
$beforeYml = Yaml::parseFile(__DIR__ . '/../../before.yml');
// $value = Yaml::parse($yaml, Yaml::PARSE_OBJECT_FOR_MAP);
// $value = Yaml::parse("foo: bar", Yaml::PARSE_OBJECT_FOR_MAP);
print_r($afterYml);
print_r($beforeYml);

$beforeYmlFile = Yaml::dump($beforeYml); // пишет из объекта, без флага будет массив

// print_r($yaml);
file_put_contents(__DIR__ . '/../../beforeYmlFile.yml', $beforeYmlFile);
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
                    $compareJson1InJson2["    " . $key1] = $vol1;
                }
                if ($key1 == $key2 && $vol1 != $vol2) {
                    $compareJson1InJson2["  + " . $key2] = $vol2;
                    $compareJson1InJson2["  - " . $key1] = $vol1;
                }
            } else {
                $compareJson1InJson2["  - " . $key1] = $vol1;
            }
        }
    }

    $searchNewDataInJson2 = [];
    foreach ($json2 as $key2 => $vol2) {
        if (!array_key_exists("    " . $key2, $compareJson1InJson2)) {
            $searchNewDataInJson2["  + " . $key2] = $vol2;
        }
    }

    $strJson = array_merge($compareJson1InJson2, $searchNewDataInJson2);
    return $strJson;
}


// пробовал сравнить объект и записать в файл
var_dump(genDiff2($beforeYml, $afterYml));
$newYamlDiff = genDiff2($beforeYml, $afterYml);

// $valueYaml = Yaml::parse($newYaml, Yaml::PARSE_OBJECT_FOR_MAP);
$diffYamlWrite = Yaml::dump($newYamlDiff);
file_put_contents(__DIR__ . '/../../diffYamlWrite.yml', $diffYamlWrite);

$testArr = [
    'test' => 'test',
    'bool' => true,
    'int' => 56,
];
$arrYamlPrint = Yaml::dump($testArr);
file_put_contents(__DIR__ . '/../../arrYamlPrint.yml', $arrYamlPrint);

function arrInFor()
{
    $res = [];
    for ($i = 0; $i < 5; $i++) {
        $res["test" . "{$i}"] = $i;
    }
    return $res;
}

$arrInFor = arrInFor();
$arrInForPrint = Yaml::dump($arrInFor);
file_put_contents(__DIR__ . '/../../arrInForPrint.yml', $arrInForPrint);
