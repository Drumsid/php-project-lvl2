<?php

namespace Differ\Differ;

use function Differ\builder\builder;
use function Differ\parsers\parseData;
use function Differ\formatters\format;

function genDiff(string $filePathBefore, string $filePathAfter, string $format = 'stylish'): string
{
    $beforeObj = parseData(getFileData($filePathBefore));
    $afterObj = parseData(getFileData($filePathAfter));

    $tree = builder($beforeObj, $afterObj);
    return format($format, $tree);
}

function getFileData(string $filePath): array
{
    // $result = [];
    // if (file_exists($filePath)) {
    //     $result['extension'] = pathinfo($filePath, PATHINFO_EXTENSION);
    //     $result['data'] = file_get_contents($filePath);
    // } else {
    //     throw new \Exception("File does not exist!");
    // }
    if (! file_exists($filePath)) {
        throw new \Exception("File does not exist!");
    }
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    $data = file_get_contents($filePath);
    // $result['extension'] = $extension;
    // $result['data'] = $data;
    return ['extension' => $extension, 'data' => $data];
}
