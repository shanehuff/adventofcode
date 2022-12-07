<?php
require_once __DIR__ . '/vendor/autoload.php';

$commandHistories = include __DIR__ . '/stubs/input.php';

$storage = new FileSystem();

$storage->makeFromCommandHistories($commandHistories);

// Part 1
//dd($files->directoriesSmallerThan(100000)->sum('size'));

// Part 2
//dd($files->directoriesCanBeFreeUp(30000000)->sum('size'));

$storage->renderTrees();
