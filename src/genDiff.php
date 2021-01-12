<?php

namespace Differ\genDiff;

use function Differ\builder\builder;
use function Differ\parsers\parseData;
use function Differ\formatters\renderFormat;

function genDiff($filePathBefore, $filePathAfter, $format = 'stylish')
{
    $beforeObj = parseData(getFileData($filePathBefore));
    $afterObj = parseData(getFileData($filePathAfter));

    $tree = builder($beforeObj, $afterObj);
    return renderFormat($format, $tree);
}

function getFileData($filePath)
{
    $res = [];
    if (file_exists($filePath)) {
        $res['extension'] = pathinfo($filePath, PATHINFO_EXTENSION);
        $res['data'] = file_get_contents($filePath);
    }
    return $res;
}
