<?php
require_once __DIR__ . '/vendor/autoload.php';

$input = file_get_contents(__DIR__ . '/input.txt');

$input = explode("\n\n", $input);

$items = [];
$operations = [];
$tests = [];
$divisions = [];
$trues = [];
$falses = [];
$originals = [];

foreach ($input as $monkey) {
    $parts = explode("\n", $monkey);
    $items[] = array_map(function ($item) {
        return (int)$item;
    }, explode(',', explode(':', $parts[1])[1]));
    $originals = $items;
    $operations[] = trim(explode('=', $parts[2])[1]);
    $divisions[] = (int)array_slice(explode(' ', $parts[3]), -1)[0];
    $trues[] = (int)array_slice(explode(' ', $parts[4]), -1)[0];
    $falses[] = (int)array_slice(explode(' ', $parts[5]), -1)[0];
}

// Part 1
$count = [];
for ($i = 0; $i < count($originals); $i++) {
    $count[$i] = 0;
}

for ($k = 0; $k < 20; $k++) {
    for ($i = 0; $i <= count($originals); $i++) {
        if (count($items) && isset($items[$i])) {
            foreach ($items[$i] as $item) {
                $count[$i] += 1;
                $item = floor(eval('return ' . str_replace('old', $item, $operations[$i]) . ';') / 3);
                if ($item % $divisions[$i] === 0) {
                    $items[$trues[$i]][] = $item;
                } else {
                    $items[$falses[$i]][] = $item;
                }
            }
        }
        $items[$i] = [];
    }
}
sort($count);

var_dump(array_slice($count, -1)[0] * array_slice($count, -2)[0]);

// Part 2

$count = [];

for ($i = 0; $i < count($originals); $i++) {
    $count[$i] = 0;
}

$bn = 1;

foreach ($divisions as $division) {
    $bn *= $bn * $division;
}

for ($k = 0; $k < 10000; $k++) {
    for ($i = 0; $i <= count($originals); $i++) {
        if (count($items) && isset($items[$i])) {
            foreach ($items[$i] as $item) {
                $count[$i] += 1;
                $item = eval('return ' . str_replace('old', $item, $operations[$i]) . ';');
                $item = fmod($item, $bn);
                if ($item % $divisions[$i] === 0) {
                    $items[$trues[$i]][] = $item;
                } else {
                    $items[$falses[$i]][] = $item;
                }
            }
        }
        $items[$i] = [];
    }
}

sort($count);

var_dump(array_slice($count, -1)[0] * array_slice($count, -2)[0]);





