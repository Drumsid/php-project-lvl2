<?php

namespace Differ\parsers;

use Symfony\Component\Yaml\Yaml;

function parseData($dataFile)
{
    ['extension' => $extension, 'data' => $data] = $dataFile;
    switch ($extension) {
        case 'yml':
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        case 'yaml':
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        case 'json':
            return json_decode($data);
        default:
            die("Extension {$extension} not supported, or the file does not exist!");
    }
}
