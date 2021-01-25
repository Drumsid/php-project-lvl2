<?php

namespace Differ\formatters\json;

function render(array $tree): string
{
    return json_encode($tree);
}
