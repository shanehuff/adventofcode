<?php
$input = file_get_contents(__DIR__ . '/input.txt');
$input = explode("\n", $input);

$input = array_map(function ($line) {
    return str_split($line);
}, $input);

return $input;