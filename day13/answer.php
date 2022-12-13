<?php
require_once __DIR__ . '/vendor/autoload.php';

$input = explode("\n\n", file_get_contents(__DIR__ . '/test.txt'));

function compare(array $first, array $second): ?bool
{
    //echo json_encode($first) . ' vs ' . json_encode($second) . PHP_EOL;
    $zip = array_map(null, $first, $second);
    $zip = array_filter($zip, function ($item) {
        if (is_array($item)) {
            return !in_array(null, $item);
        }
        return $item;
    });
    //echo json_encode($zip) . PHP_EOL;

    foreach ($zip as [$x, $y]) {

        if (is_array($x) && is_array($y)) {
            $res = compare($x, $y);
            if (!is_null($res)) {
                return $res;
            }
        } elseif (is_array($x)) {
            $res = compare($x, [$y]);
            if (!is_null($res)) {
                return $res;
            }
        } elseif (is_array($y)) {
            $res = compare([$x], $y);
            if (!is_null($res)) {
                return $res;
            }
        } else {
            if ($x < $y) {
                return true;
            }

            if ($x > $y) {
                return false;
            }
        }
    }

    if (count($first) < count($second)) {
        return true;
    }

    if (count($first) > count($second)) {
        return false;
    }

    return null;
}

$sum = [];

foreach ($input as $i => $pair) {
    $i++;
    //var_dump($i);
    [$first, $second] = explode("\n", $pair);
    $sum[] = true === compare(eval('return ' . $first . ';'), eval('return ' . $second . ';')) ? $i : 0;
}

// Part 1
var_dump(array_sum($sum));

$a = [];
foreach ($input as $pair) {
    foreach (explode("\n", $pair) as $x) {
        $a[] = eval('return ' . $x . ';');
    }
}

$a = array_merge($a, [[[2]], [[6]]]);
$p2 = [];

while (count($a) > 0) {
    // $x is the first item of $a but do not use array_shift and do not use $a[0]
    $x = array_slice($a, 0, 1)[0];
    foreach ($a as $y) {
        if (!is_null($x) && compare($x, $y) !== true) {
            $x = $y;
        }
    }

    $p2[] = $x;
    // Remove item $x from $a
    $a = array_filter($a, function ($item) use ($x) {
        return $item !== $x;
    });
}

array_unshift($p2, 'sb');

$index = array_map(function ($item) use ($p2) {
    return array_search($item, $p2);
}, [[2]], [[6]]);

var_dump($index[0], $index[1], $index[0] * $index[1]);