<?php

function readInput($filePath) {
    return file_get_contents($filePath);
}

function tiltNorth($i, $j, &$grid) {
    while ($i > 0 && $grid[$i - 1][$j] == ".") {
        $grid[$i][$j] = ".";
        $grid[$i - 1][$j] = "O";
        $i--;
    }
    return [$i, $j];
}

function tiltWest($i, $j, &$grid) {
    while ($j > 0 && $grid[$i][$j - 1] == ".") {
        $grid[$i][$j] = ".";
        $grid[$i][$j - 1] = "O";
        $j--;
    }
    return [$i, $j];
}

function tiltSouth($i, $j, &$grid) {
    while ($i < count($grid) - 1 && $grid[$i + 1][$j] == ".") {
        $grid[$i][$j] = ".";
        $grid[$i + 1][$j] = "O";
        $i++;
    }
    return [$i, $j];
}

function tiltEast($i, $j, &$grid) {
    while ($j < count($grid[0]) - 1 && $grid[$i][$j + 1] == ".") {
        $grid[$i][$j] = ".";
        $grid[$i][$j + 1] = "O";
        $j++;
    }
    return [$i, $j];
}

function calcLoad($grid) {
    $load = 0;
    $rowCount = count($grid);
    foreach (array_reverse($grid) as $i => $row) {
        $load += ($i + 1) * substr_count(implode("", $row), "O");
    }
    return $load;
}

function cycle($rocks, &$grid) {
    $keys = [
        function ($x) { return $x[1]; },
        function ($x) { return -$x[0]; },
        function ($x) { return -$x[1]; },
        function ($x) { return $x[0]; },
    ];
    foreach ([tiltNorth, tiltWest, tiltSouth, tiltEast] as $k => $f) {
        $nRocks = [];
        foreach ($rocks as list($i, $j)) {
            list($ni, $nj) = $f($i, $j, $grid);
            $nRocks[] = [$ni, $nj];
        }
        $rocks = array_values(array_sort($nRocks, $keys[$k % 4]));
    }
    return [$rocks, $grid];
}

function findCycle($rocks, &$grid) {
    $seen = [implode("", array_map("implode", $grid)) => 0];
    foreach (count_chars($seen, 1) as $i => $count) {
        if ($count > 1) {
            return [$seen[$i], $i - $seen[$i], $seen];
        }
    }
    return null;
}

function parseGrid($data) {
    return array_map("str_split", explode("\n", trim($data)));
}

function getRocks($grid) {
    $rocks = [];
    foreach ($grid as $i => $row) {
        foreach ($row as $j => $cell) {
            if ($cell == "O") {
                $rocks[] = [$i, $j];
            }
        }
    }
    return $rocks;
}

function partOne() {
    $grid = parseGrid(readInput("14.txt"));
    $rocks = getRocks($grid);
    foreach ($rocks as list($i, $j)) {
        tiltNorth($i, $j, $grid);
    }
    return calcLoad($grid);
}

function partTwo() {
    $grid = parseGrid(readInput("14.txt"));
    $rocks = getRocks($grid);
    $result = findCycle($rocks, $grid);
    if ($result !== null) {
        list($start, $length, $seen) = $result;
        $rSeen = array_flip($seen);
        $res = $rSeen[$start + (1000000000 - $start) % $length];
        return calcLoad(parseGrid($res));
    }
    return null;
}

echo "Part 1: " . partOne() . PHP_EOL;  // 112773
echo "Part 2: " . partTwo() . PHP_EOL;  // 98894
