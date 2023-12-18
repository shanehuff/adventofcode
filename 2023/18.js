const fs = require('fs');

// Read lines from the file '18.txt' and store them in a list
const lines = fs.readFileSync('18.txt', 'utf-8').trim().split('\n');

// Define the directions (right, down, left, up) and their corresponding symbols
const directions = [[0, 1], [1, 0], [0, -1], [-1, 0]];
const direction_symbols = ['R', 'D', 'L', 'U'];

function calculate_area(part2_mode) {
    // Initialize variables
    const boundary = new Set();
    let current_position = [0, 0];
    const points = [current_position];
    let total_perimeter = 0;

    // Process each line in the input
    for (const line of lines) {
        let direction, distance;  // Declare variables here

        if (part2_mode) {
            // Extract direction and distance from the line (part 2 mode)
            const line_data = line.split("#")[1].split(")")[0];
            direction = directions[parseInt(line_data.slice(-1))];
            distance = parseInt(line_data.slice(0, -1), 16);
        } else {
            // Extract direction and distance from the line (part 1 mode)
            direction = directions[direction_symbols.indexOf(line.split(" ")[0])];
            distance = parseInt(line.split(" ")[1]);
        }

        // Update current position and total perimeter
        current_position = [
            current_position[0] + direction[0] * distance,
            current_position[1] + direction[1] * distance
        ];
        total_perimeter += distance;
        points.push([...current_position]);
    }

    // Reverse the points to calculate the area
    points.reverse();
    let area = 0;

    // Calculate the area using the shoelace formula
    for (let i = 0; i < points.length - 1; i++) {
        area += (points[i][1] + points[i + 1][1]) * (points[i][0] - points[i + 1][0]);
    }

    // Print the final result
    console.log(Math.floor(total_perimeter / 2 + area / 2 + 1));
}

// Run the function for both part 1 and part 2
calculate_area(false);
calculate_area(true);
