<?php

// Read lines from the file '18.txt' and store them in a list
$lines = file('18.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Define the directions (right, down, left, up) and their corresponding symbols
$directions = array(array(0, 1), array(1, 0), array(0, -1), array(-1, 0));
$direction_symbols = array('R', 'D', 'L', 'U');

function calculate_area($part2_mode)
{
    // Initialize variables
    $boundary = array();
    $current_position = array(0, 0);
    $points = array($current_position);
    $total_perimeter = 0;

    // Process each line in the input
    global $lines, $directions, $direction_symbols;
    foreach ($lines as $line) {
        if ($part2_mode) {
            // Extract direction and distance from the line (part 2 mode)
            $line_data = explode(")", explode("#", $line)[1])[0];
            $direction = $directions[(int)substr($line_data, -1)];
            $distance = hexdec(substr($line_data, 0, -1));
        } else {
            // Extract direction and distance from the line (part 1 mode)
            $direction = $directions[array_search(explode(" ", $line)[0], $direction_symbols)];
            $distance = (int)explode(" ", $line)[1];
        }

        // Update current position and total perimeter
        $current_position = array(
            $current_position[0] + $direction[0] * $distance,
            $current_position[1] + $direction[1] * $distance
        );
        $total_perimeter += $distance;
        $points[] = $current_position;
    }

    // Reverse the points to calculate the area
    $points = array_reverse($points);
    $area = 0;

    // Calculate the area using the shoelace formula
    for ($i = 0; $i < count($points) - 1; $i++) {
        $area += ($points[$i][1] + $points[$i + 1][1]) * ($points[$i][0] - $points[$i + 1][0]);
    }

    // Print the final result
    echo ($total_perimeter / 2 + $area / 2 + 1) . PHP_EOL;
}

// Run the function for both part 1 and part 2
calculate_area(false);
calculate_area(true);

?>
