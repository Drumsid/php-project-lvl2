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
$value = Yaml::parseFile(__DIR__ . '/../../after.yml', Yaml::PARSE_OBJECT_FOR_MAP); // парсит из Yaml в объект, без флага будет массив
// $value = Yaml::parse($yaml, Yaml::PARSE_OBJECT_FOR_MAP);
// $value = Yaml::parse("foo: bar", Yaml::PARSE_OBJECT_FOR_MAP);
print_r($value);

$yaml = Yaml::dump($value, 2, 4, Yaml::DUMP_OBJECT_AS_MAP); // пишет из объекта, без флага будет массив

print_r($yaml);
file_put_contents(__DIR__ . '/../../test.yml', $yaml);