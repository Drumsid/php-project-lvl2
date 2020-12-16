<?php

namespace Differ\differ\genDiff;

use function Differ\differ\builder\builder;
use function Differ\differ\parsers\checkExpansion;
use function Differ\formaters\formatters\checkFormat;

function genDiff($fileBefore, $fileAfter, $format = 'stylish')
{
    $beforeObj = checkExpansion($fileBefore);
    $afterObj = checkExpansion($fileAfter);

    $tree = builder($beforeObj, $afterObj);

    return checkFormat($format, $tree);
}
