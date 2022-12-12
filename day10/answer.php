<?php
require_once __DIR__ . '/vendor/autoload.php';

$input = file_get_contents(__DIR__ . '/input.txt');
$lines = explode("\n", $input);

$cycles = 0;
$regx = 1;
$xs = [];

function display($cycles, $regx)
{
    $px = $cycles % 40;

    if (0 === $px) {
        echo "\n";
    }

    if ($regx - 1 <= $px && $px <= $regx + 1) {
        echo '#';
    } else {
        echo ' ';
    }

    echo '';
}

foreach ($lines as $line) {
    $cmd = explode(' ', $line);

    display($cycles, $regx);

    switch ($cmd[0]) {
        case 'noop':
            $xs[] = $regx;
            $cycles++;
            break;
        case 'addx':
            $xs[] = $regx;
            $cycles++;

            display($cycles, $regx);

            $regx += (int)$cmd[1];
            $xs[] = $regx;
            $cycles++;
            break;
    }
}
echo "\n";

$strength = 0;

for ($i = 20; $i < 221; $i += 40) {
    $strength += $xs[$i - 2] * $i;
}

dd($strength);






