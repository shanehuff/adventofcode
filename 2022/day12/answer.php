<?php
require_once __DIR__ . '/vendor/autoload.php';

$input = file_get_contents(__DIR__ . '/input.txt');
$g = array_map(function ($line) {
    return str_split($line);
}, explode("\n", $input));
$rows = count($g[0]);
$cols = count($g);
$directions = [
    [0, 1],
    [1, 0],
    [0, -1],
    [-1, 0]
];

$s = [];
for ($i = 0; $i < $cols; $i++) {
    for ($j = 0; $j < $rows; $j++) {
        if ($g[$i][$j] == 'S') {
            $s = [$i, $j];
            break;
        }
    }
}

[$sx, $sy] = $s;

$e = [];
for ($i = 0; $i < $cols; $i++) {
    for ($j = 0; $j < $rows; $j++) {
        if ($g[$i][$j] == 'E') {
            $e = [$i, $j];
            break;
        }
    }
}

[$tx, $ty] = $e;

$g[$sx][$sy] = 'a';
$g[$tx][$ty] = 'z';

$best = 10000;

for ($i = 0; $i < $cols; $i++) {
    for ($j = 0; $j < $rows; $j++) {
        if ($g[$i][$j] !== 'a') {
            continue;
        }

        foreach ($g as $r => $row) {
            foreach ($row as $c => $char) {
                $g[$r][$c] = ord($char) - 97;
            }
        }

        $d = new DefaultDict(1000000);
        $d[$i . $j] = 0;

        $answer = 100000;

        $queue = new SplDoublyLinkedList();
        $queue->push([$i, $j]);

        while (count($queue) > 0) {
            [$x, $y] = $queue->shift();

            if ([$x, $y] === [$tx, $ty]) {
                $answer = $d[$tx . $ty];
                if ([$i, $j] === [$sx, $sy]) {
                    var_dump('p1: '.$answer);
                }
                break;
            }

            foreach ($directions as [$dx, $dy]) {
                $nx = $x + $dx;
                $ny = $y + $dy;

                if (0 <= $nx && $nx < $cols && 0 <= $ny && $ny < $rows) {
                    if ($g[$x][$y] >= $g[$nx][$ny] - 1) {
                        $nd = $d[$x . $y] + 1;
//                        var_dump('nd:'.$nd);
//                        var_dump('od:'.$d[$x.$y]);
                        if ($nd < $d[$nx . $ny]) {
                            $d[$nx . $ny] = $nd;
                            $queue->push([$nx, $ny]);
                        }
                    }
                }
            }

            $best = min($best, $answer);
        }
    }
}

dd($best);