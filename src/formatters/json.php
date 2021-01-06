<?php

namespace Differ\formatters\json;

function jsonFormat($tree)
{
    return json_encode($tree);
}

function render($arr)
{
    return jsonFormat($arr);
}
