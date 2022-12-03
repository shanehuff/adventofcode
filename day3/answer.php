<?php
require_once __DIR__ . '/vendor/autoload.php';

$rucksacks = collect(include __DIR__ . '/stubs/input.php');

$part1 = $rucksacks->map(function ($data) {
    return (new Rucksack($data))->toArray();
});

echo $part1->sum('priority');

$part2 = collect();

$rucksacks->chunk(3)->each(function ($chunk) use (&$part2) {
    $part2->push((new Group($chunk->values()))->toArray());
});

echo $part2->sum('priority');
