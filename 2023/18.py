# Read lines from the file '18.txt' and store them in a list
lines = [x for x in open('18.txt').read().strip().split('\n')]

# Define the directions (right, down, left, up) and their corresponding symbols
DIRECTIONS = [(0, 1), (1, 0), (0, -1), (-1, 0)]
DIRECTION_SYMBOLS = ['R', 'D', 'L', 'U']

def calculate_area(part2_mode):
    # Initialize variables
    boundary = set()
    current_position = (0, 0)
    points = [current_position]
    total_perimeter = 0

    # Process each line in the input
    for line in lines:
        if part2_mode:
            # Extract direction and distance from the line (part 2 mode)
            line_data = line.split("#")[1].split(")")[0]
            direction = DIRECTIONS[int(line_data[-1])]
            distance = int(line_data[:-1], 16)
        else:
            # Extract direction and distance from the line (part 1 mode)
            direction = DIRECTIONS[DIRECTION_SYMBOLS.index(line.split(" ")[0])]
            distance = int(line.split(" ")[1])

        # Update current position and total perimeter
        current_position = (current_position[0] + direction[0] * distance, current_position[1] + direction[1] * distance)
        total_perimeter += distance
        points.append(current_position)

    # Reverse the points to calculate the area
    points = points[::-1]
    area = 0

    # Calculate the area using the shoelace formula
    for i in range(len(points) - 1):
        area += (points[i][1] + points[i + 1][1]) * (points[i][0] - points[i + 1][0])

    # Print the final result
    print(total_perimeter // 2 + area // 2 + 1)

# Run the function for both part 1 and part 2
calculate_area(part2_mode=False)
calculate_area(part2_mode=True)
