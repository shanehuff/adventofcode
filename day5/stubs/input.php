<?php
function parse_crate($crates): array
{
    // preg_match all capital letters inside the string '[ ]'
    preg_match_all('/\[([A-Z_]+)\]/', $crates, $matches);
    $crates = [];
    foreach ($matches[1] as $index => $crate) {
        $crates[$index + 1] = '_' === $crate ? null : $crate;
    }
    return $crates;
}

$crates = array_map(function ($line) {
    return parse_crate(preg_replace('/ {4}/', ' [_]', $line));
}, array_filter(explode(PHP_EOL, file_get_contents(__DIR__ . '/crate-input.txt'))));

$moves = array_map(function ($line) {
    preg_match('/move (\d{1,2}?) from (\d{1,2}?) to (\d{1,2}?)/', $line, $matches);

    return [
        (int)$matches[1],
        (int)$matches[2],
        (int)$matches[3],
    ];
}, array_filter(explode(PHP_EOL, file_get_contents(__DIR__ . '/move-input.txt'))));

return [
    $crates,
    $moves
];