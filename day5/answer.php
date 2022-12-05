<?php
require_once __DIR__ . '/vendor/autoload.php';

$inputs = collect(include __DIR__ . '/stubs/input.php');


//$stackPart1 = new Stack;
//$stackPart1->setCrates($inputs->get('crates'));
//$stackPart1->setMoves($inputs->get('moves'));
//$stackPart1->moveOneAtATime();
//dd($stackPart1->toLetters());

$stackPart2 = new Stack;
$stackPart2->setCrates($inputs->get('crates'));
$stackPart2->setMoves($inputs->get('moves'));


$stackPart2->moveMultipleAtATime();

dd($stackPart2->toLetters());


