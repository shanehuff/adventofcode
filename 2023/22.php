<?php

function extract_integers($s) {
    preg_match_all('/\d+/', $s, $matches);
    return array_map('intval', $matches[0]);
}

function move_brick_down($brick) {
    return [$brick[0], $brick[1], $brick[2] - 1, $brick[3], $brick[4], $brick[5] - 1, $brick[6]];
}

function generate_brick_positions($brick) {
    $positions = [];
    for ($x = $brick[0]; $x <= $brick[3]; $x++) {
        for ($y = $brick[1]; $y <= $brick[4]; $y++) {
            for ($z = $brick[2]; $z <= $brick[5]; $z++) {
                $positions[] = [$x, $y, $z];
            }
        }
    }
    return $positions;
}

// Parse input file and add index to each brick
$file_contents = file_get_contents('22.txt');
$lines = explode("\n", trim($file_contents));
$bricks_with_index = [];
foreach ($lines as $index => $line) {
    $bricks_with_index[] = extract_integers($line) + [$index];
}

$occupied_positions = [];
$fallen_bricks = [];

// Process bricks in sorted order based on the third coordinate
usort($bricks_with_index, function ($a, $b) {
    return $a[2] - $b[2];
});

foreach ($bricks_with_index as $brick) {
    while (true) {
        $next_brick = move_brick_down($brick);
        // Check if the next position is valid and update the brick
        $next_brick_positions = generate_brick_positions($next_brick);
        if (!array_intersect($next_brick_positions, $occupied_positions) && $next_brick[2] > 0) {
            $brick = $next_brick;
        } else {
            // Mark the positions of the current brick as occupied and add it to the fallen list
            $brick_positions = generate_brick_positions($brick);
            $occupied_positions = array_merge($occupied_positions, $brick_positions);
            $fallen_bricks[] = $brick;
            break;
        }
    }
}

$above = [];
$below = [];

// Create relationships between bricks above and below
foreach ($fallen_bricks as $brick) {
    $positions_in_current_brick = generate_brick_positions($brick);
    $positions_in_lower_brick = generate_brick_positions(move_brick_down($brick));

    foreach ($positions_in_lower_brick as $pos) {
        if (in_array($pos, $occupied_positions) && !in_array($pos, $positions_in_current_brick)) {
            $above[$pos] = $brick;
            $below[$brick][] = $pos;
        }
    }
}

// Function to find the number of bricks that would fall if a given brick disintegrated
function calculate_falling_bricks($disintegrated, &$falling_bricks) {
    if (in_array($disintegrated, $falling_bricks)) {
        return;
    }

    $falling_bricks[] = $disintegrated;
    global $above, $below;

    foreach ($above[$disintegrated] as $parent) {
        if (count(array_diff($below[$parent], $falling_bricks)) === 0) {
            calculate_falling_bricks($parent, $falling_bricks);
        }
    }
}

$p1 = 0;
$p2 = 0;

// Calculate the number of falling bricks for each fallen brick
foreach ($fallen_bricks as $brick) {
    $falling_bricks = [];
    calculate_falling_bricks($brick, $falling_bricks);
    $would_fall = count($falling_bricks);

    $p1 += ($would_fall == 1) ? 1 : 0;
    $p2 += ($would_fall - 1);
}

// Print the results
echo "Part 1: $p1\n";
echo "Part 2: $p2\n";

?>
