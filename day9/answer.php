<?php
require_once __DIR__ . '/vendor/autoload.php';

$motions = include __DIR__ . '/stubs/input.php';

$diagram = new Diagram;

$diagram->loadMotions($motions);

$diagram->run();

dd(collect($diagram->toArray()['tracker']['tail'])->unique()->count());
