<?php
require_once __DIR__ . '/vendor/autoload.php';

$trees = include __DIR__ . '/stubs/input.php';

$map = new Map;

$map->create($trees);

echo $map->renderAbove();

// Part 1
echo $map->countVisible();