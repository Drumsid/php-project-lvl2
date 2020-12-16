<?php

namespace Differ\differ\parsers;

use Symfony\Component\Yaml\Yaml;

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
