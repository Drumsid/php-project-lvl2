<?php

namespace Differ\differ\Parsers;

function parsing($beforeFile, $afterFile)
{
    $compareBeforeInAfter = [];
    foreach ($beforeFile as $keyBefore => $volBefore) {
        foreach ($afterFile as $keyAfter => $volAfter) {
            if (array_key_exists($keyBefore, $afterFile)) {
                if ($keyBefore == $keyAfter && $volBefore == $volAfter) {
                    $compareBeforeInAfter["    " . $keyBefore] = " " . $volBefore;
                }
                if ($keyBefore == $keyAfter && $volBefore != $volAfter) {
                    $compareBeforeInAfter["  + " . $keyAfter] = " " . $volAfter;
                    $compareBeforeInAfter["  - " . $keyBefore] = " " . $volBefore;
                }
            } else {
                $compareBeforeInAfter["  - " . $keyBefore] = " " . $volBefore;
            }
        }
    }

    $searchNewDataInAfter = [];
    foreach ($afterFile as $keyAfter => $volAfter) {
        if (!array_key_exists("    " . $keyAfter, $compareBeforeInAfter)) {
            $searchNewDataInAfter["  + " . $keyAfter] =
            $volAfter == is_bool($volAfter) ? " " . json_encode($volAfter) : " " . $volAfter;
        }
    }

    $strJson = json_encode(array_merge($compareBeforeInAfter, $searchNewDataInAfter));
    return $strJson;
}

function correctCurleBrackets($str, $delimiter)
{
    $search = "";
    for ($i = 0; $i < strlen($str); $i++) {
        $search .= $str[$i];
        if ($i == 0) {
            $search .= $delimiter;
        }
        if ($i == strlen($str) - 2) {
            $search .= $delimiter;
        }
    }
    return $search;
}

// тут я тестирую xdebug
$beforeFile = json_decode(file_get_contents(__DIR__ . "\..\..\before2.json"), true);
$afterFile = json_decode(file_get_contents(__DIR__ . "\..\..\after2.json"), true);
// print_r(parsing($beforeFile, $afterFile));