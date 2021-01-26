<?php

namespace Differ\Differ;

use function Differ\Builder\builder;
use function Differ\Parsers\parseData;
use function Differ\Formatters\format;

function genDiff(string $filePathBefore, string $filePathAfter, string $format = 'stylish'): string
{
    $beforeObj = parseData(getFileData($filePathBefore));
    $afterObj = parseData(getFileData($filePathAfter));

    $tree = builder($beforeObj, $afterObj);
    return format($format, $tree);
}

function getFileData(string $filePath): array
{
    if (! file_exists($filePath)) {
        throw new \Exception("File does not exist!");
    }
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    $data = file_get_contents($filePath);

    return ['extension' => $extension, 'data' => $data];
}
