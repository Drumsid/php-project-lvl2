<?php

namespace Differ\parsers;

use Symfony\Component\Yaml\Yaml;

function parseData($dataFile)
{
    ['extension' => $extension, 'data' => $data] = $dataFile;
    switch ($extension) {
        case 'yml':
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
            break;
        case 'json':
            return json_decode($data);
            break;
    }
}
