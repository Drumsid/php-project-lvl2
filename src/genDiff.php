<?php

// namespace Differ\genDiff;
namespace Differ\Differ;

use function Differ\builder\builder;
use function Differ\parsers\parseData;
use function Differ\formatters\format;

function genDiff($filePathBefore, $filePathAfter, $format = 'stylish')
{
    $beforeObj = parseData(getFileData($filePathBefore));
    $afterObj = parseData(getFileData($filePathAfter));

    $tree = builder($beforeObj, $afterObj);
    return format($format, $tree);
}

function getFileData($filePath)
{
    $result = [];
    if (file_exists($filePath)) {
        $result['extension'] = pathinfo($filePath, PATHINFO_EXTENSION);
        $result['data'] = file_get_contents($filePath);
    } else {
        throw new \Exception("File does not exist!");
    }
    return $result;
}
