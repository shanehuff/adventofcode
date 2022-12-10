<?php
require_once __DIR__ . '/vendor/autoload.php';

$trees = include __DIR__ . '/stubs/input.php';

$map = new Map;

$map->create($trees);

// Part 1
echo $map->countVisible();
echo PHP_EOL;

// Part 2
echo $map->getHighestScenicScore();
echo PHP_EOL;