<?php

namespace Differ\differ\genDiff;

use function Differ\differ\builder\builder;
use function Differ\differ\parsers\parseData;
use function Differ\formatters\formatters\renderFormat;

function genDiff($fileBefore, $fileAfter, $format = 'stylish')
{
    $beforeObj = parseData($fileBefore);
    $afterObj = parseData($fileAfter);

    $tree = builder($beforeObj, $afterObj);

    return renderFormat($format, $tree);
}
