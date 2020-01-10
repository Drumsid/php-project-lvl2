<?php

namespace Differ\differ\Yamlic;

use Symfony\Component\Yaml\Yaml;

use function Differ\differ\Parsers\parsing;
use function Differ\differ\Parsers\correctCurleBrackets;

const CORRECT_PATH_YAML = __DIR__ . "/../../";

// $autoloadPath1 = __DIR__ . '/../../autoload.php';    // без этой записи в bin/gendiff функция parseYml работает
// $autoloadPath2 = __DIR__ . '/../../vendor/autoload.php';  // но если без этой записи вызвать
// if (file_exists($autoloadPath1)) {                       // в этом файле функцию parseYml - ошибка
//     require_once $autoloadPath1;
// } else {
//     require_once $autoloadPath2;
// }

function parseYml($beforeYml, $afterYml)
{
    // доделать для абсолютных и относительных путей
    $beforeYml = Yaml::parseFile(CORRECT_PATH_YAML . $beforeYml, Yaml::PARSE_OBJECT_FOR_MAP);
    $afterYml = Yaml::parseFile(CORRECT_PATH_YAML . $afterYml, Yaml::PARSE_OBJECT_FOR_MAP);

    $strJson = parsing($beforeYml, $afterYml);

    $tmp = correctCurleBrackets(str_replace(',', PHP_EOL, $strJson), PHP_EOL);
    return str_replace('"', "", $tmp);
}

// print_r(parseYml("before.yml", "after.yml"));
