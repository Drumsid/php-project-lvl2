<?php

namespace Differ\differ\parsers;

use Symfony\Component\Yaml\Yaml;

function parseData($file)
{

    // if (substr($file, -4) == ".yml" && file_exists($file)) {
    //     return Yaml::parseFile($file, Yaml::PARSE_OBJECT_FOR_MAP);
    // }
    // if (substr($file, -5) == ".json" && file_exists($file)) {
    //     return json_decode(file_get_contents($file));
    // }
    if ('yml' == checkExpansion($file)) {
        return Yaml::parseFile($file, Yaml::PARSE_OBJECT_FOR_MAP);
    }
    if ('json' == checkExpansion($file)) {
        return json_decode(file_get_contents($file));
    }
    // $exp = checkExpansion($file);
    //  switch ($exp) {
    //     case 'yml':
    //         return Yaml::parseFile($file, Yaml::PARSE_OBJECT_FOR_MAP);
    //     case 'json':
    //         return json_decode(file_get_contents($file));
    // }
}

function checkExpansion($file)
{
    if (substr($file, -4) == ".yml" && file_exists($file)) {
        return "yml";
    }
    if (substr($file, -5) == ".json" && file_exists($file)) {
        return "json";
    }
}