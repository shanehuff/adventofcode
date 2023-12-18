<?php

// Function to draw a colorful picture based on movement instructions
function draw_colorful_picture($instructions) {
    $grid = array();
    $current_position = array(0, 0);
    $colors = array();

    // Set the starting point in the grid with a default color
    $grid[$current_position[0]][$current_position[1]] = '*';
    $colors[$current_position[0]][$current_position[1]] = '#000000'; // Default color is black

    // Loop through each instruction
    foreach ($instructions as $instruction) {
        $direction = $instruction[0];
        $distance = $instruction[1];
        $color = $instruction[2] ?? '#000000'; // Default color is black if not provided

        // Update position based on the instruction
        switch ($direction) {
            case 'R':
                $current_position[0] += $distance;
                break;
            case 'D':
                $current_position[1] += $distance;
                break;
            case 'L':
                $current_position[0] -= $distance;
                break;
            case 'U':
                $current_position[1] -= $distance;
                break;
            // Add more cases for other directions if needed
        }

        // Mark the position in the grid with the specified color
        $grid[$current_position[0]][$current_position[1]] = '*';
        $colors[$current_position[0]][$current_position[1]] = $color;
    }

    // Display the grid with colors
    foreach ($grid as $x => $row) {
        foreach ($row as $y => $cell) {
            echo '<span style="color:' . str_replace(['(', ')'], [''], $colors[$x][$y]) . ';">' . $cell . '</span>';
        }
        echo PHP_EOL;
    }
}

// Read and parse instructions from the '18.txt' file
$file_content = file_get_contents('18.txt');
$lines = explode("\n", trim($file_content));

$instructions = array();
foreach ($lines as $line) {
    $parts = explode(' ', $line);
    $direction = $parts[0];
    $distance = (int)$parts[1];
    $color = isset($parts[2]) ? $parts[2] : '#000000';
    $instructions[] = [$direction, $distance, $color];
}

// Call the function with the instructions from the file
draw_colorful_picture($instructions);

?>
