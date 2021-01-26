<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseData(array $dataFile): object
{
    ['extension' => $extension, 'data' => $data] = $dataFile;
    switch ($extension) {
        case 'yml':
        case 'yaml':
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        case 'json':
            return json_decode($data);
        default:
            throw new \Exception("Extension {$extension} not supported!");
    }
}
