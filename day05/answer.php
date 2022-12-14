<?php
require_once __DIR__ . '/vendor/autoload.php';

[$crates, $moves] = include __DIR__ . '/stubs/input.php';

$stackPart1 = new Stack;
$stackPart1->setCrates($crates);
$stackPart1->setMoves($moves);

$stackPart1->moveOneAtATime();

$stackPart1->draw();

$stackPart2 = new Stack;
$stackPart2->setCrates($crates);
$stackPart2->setMoves($moves);

$stackPart2->moveMultipleAtATime();

$stackPart2->draw();