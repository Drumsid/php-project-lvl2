<?php

namespace Differ\genDiff;

use function Differ\builder\builder;
use function Differ\parsers\parseData;
use function Differ\formatters\renderFormat;

function genDiff($fileBefore, $fileAfter, $format = 'stylish')
{
    $beforeObj = parseData($fileBefore);
    $afterObj = parseData($fileAfter);

    $tree = builder($beforeObj, $afterObj);

    return renderFormat($format, $tree);
}
