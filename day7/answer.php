<?php
require_once __DIR__ . '/vendor/autoload.php';

$commandHistories = include __DIR__ . '/stubs/input.php';

$files = new FileSystem();

$files->loadHistories($commandHistories);

//dd($files->getDeletableDirectories()->sum('size'));
//dd($files->toArray()['nodes']);
$files->renderTrees();

//dd($files->toArray()['nodes']);
