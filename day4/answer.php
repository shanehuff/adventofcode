<?php
require_once __DIR__ . '/vendor/autoload.php';

$pairs = collect(include __DIR__ . '/stubs/input.php');

$part1 = $pairs->map(function ($pair) {
    return (new Pair($pair))->toArray();
});

//dd( $part1->where('is_fully_contain_each_other', true)->count());

$part2 = $pairs->map(function ($pair) {
    return (new Pair($pair))->toArray();
});

dd($part2->where('is_overlap', true)->count());