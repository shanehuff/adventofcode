<?php

// Read input from file and initialize grid
$file_content = file_get_contents('16.txt');
$grid_lines = explode("\n", trim($file_content));
$grid = array_map('str_split', $grid_lines);

$num_rows = count($grid);
$num_columns = count($grid[0]);

// Define movement directions: up, right, down, left
$row_moves = [-1, 0, 1, 0];
$column_moves = [0, 1, 0, -1];

// Function to perform a step in the given direction
function move($row, $column, $direction) {
    global $row_moves, $column_moves;
    return [$row + $row_moves[$direction], $column + $column_moves[$direction], $direction];
}

// Function to calculate the score of a given starting position and direction
function calculate_score($start_row, $start_column, $start_direction) {
    global $grid, $num_rows, $num_columns;
    $positions = [[$start_row, $start_column, $start_direction]]; // Initialize positions
    $visited_positions = []; // Array to store visited positions
    $visited_positions_with_direction = []; // Array to store visited positions with direction

    while (true) {
        $new_positions = [];
        if (empty($positions)) {
            break;
        }

        foreach ($positions as list($row, $column, $direction)) {
            // Check if the position is within the grid
            if ($row >= 0 && $row < $num_rows && $column >= 0 && $column < $num_columns) {
                $visited_positions[] = [$row, $column];
                $current_position_with_direction = [$row, $column, $direction];

                if (in_array($current_position_with_direction, $visited_positions_with_direction, true)) {
                    continue;
                }

                $visited_positions_with_direction[] = $current_position_with_direction;
                $cell = $grid[$row][$column];

                // Move forward in the current direction if the cell is empty
                if ($cell === '.') {
                    $new_positions[] = move($row, $column, $direction);
                } elseif ($cell === '/') {
                    $new_positions[] = move($row, $column, ($direction + 3) % 4);
                } elseif ($cell === '\\') {
                    $new_positions[] = move($row, $column, ($direction + 1) % 4);
                } elseif ($cell === '|') {
                    // Move vertically if the current direction is up or down, otherwise, move up and down
                    if ($direction === 0 || $direction === 2) {
                        $new_positions[] = move($row, $column, $direction);
                    } else {
                        $new_positions[] = move($row, $column, 0);
                        $new_positions[] = move($row, $column, 2);
                    }
                } elseif ($cell === '-') {
                    // Move horizontally if the current direction is left or right, otherwise, move left and right
                    if ($direction === 1 || $direction === 3) {
                        $new_positions[] = move($row, $column, $direction);
                    } else {
                        $new_positions[] = move($row, $column, 1);
                        $new_positions[] = move($row, $column, 3);
                    }
                } else {
                    die("Unexpected cell type");
                }
            }
        }
        $positions = $new_positions;
    }

    return count($visited_positions);
}

// Calculate and print the score for the specified starting position and direction
echo calculate_score(0, 0, 1) . PHP_EOL;

// Find the maximum score by trying different starting positions and directions
$maximum_score = 0;
for ($row = 0; $row < $num_rows; $row++) {
    $maximum_score = max($maximum_score, calculate_score($row, 0, 1));
    $maximum_score = max($maximum_score, calculate_score($row, $num_columns - 1, 3));
}
for ($column = 0; $column < $num_columns; $column++) {
    $maximum_score = max($maximum_score, calculate_score(0, $column, 2));
    $maximum_score = max($maximum_score, calculate_score($num_rows - 1, $column, 0));
}
echo $maximum_score . PHP_EOL;
