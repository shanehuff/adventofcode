<?php
$input = file_get_contents(__DIR__ . '/test.txt');
$input = explode("\n", $input);

$input = array_map(function ($line) {
    [$direction, $length] = explode(' ', $line);
    return [
        'direction' => $direction,
        'length' => (int)$length,
    ];
}, $input);

return $input;