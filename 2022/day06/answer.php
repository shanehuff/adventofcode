<?php
require_once __DIR__ . '/vendor/autoload.php';

$input = include __DIR__ . '/stubs/input.php';

$devicePart1 = new Device($input, 4);

while($devicePart1->isRunning()) {
    $devicePart1->stream();
}

var_dump($devicePart1->stoppedAt());

/**
 *
 */
$devicePart2 = new Device($input, 14);

while($devicePart2->isRunning()) {
    $devicePart2->stream();
}

var_dump($devicePart2->stoppedAt());
