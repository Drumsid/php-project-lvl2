<?php

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

$doc = <<<DOC
Usage: my_program.php [-hso FILE] [--quiet | --verbose] [INPUT ...]

Options:
  -h --help    show this
  -s --sorted  sorted output
  -o FILE      specify output file [default: ./test.txt]
  --quiet      print less text
  --verbose    print more text

DOC;

$args = Docopt::handle($doc);
