<?php

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

$gendiff = <<<DOC
Naval Fate.

Usage:
  naval_fate.php ship new gendiff
  naval_fate.php (-h | --help)
  naval_fate.php --version

Options:
  -h --help     Show this screen.
  --version     Show version.

DOC;

$args = Docopt::handle($gendiff);
print_r($args);
