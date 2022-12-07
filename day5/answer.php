<?php
require_once __DIR__ . '/vendor/autoload.php';

[$crates, $moves] = include __DIR__ . '/stubs/input.php';

$stackPart1 = new Stack;
$stackPart1->setCrates($crates);
$stackPart1->setMoves($moves);

$stackPart1->moveOneAtATime();


