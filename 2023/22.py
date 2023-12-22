import re
from collections import defaultdict

def extract_integers(s):
    return list(map(int, re.findall(r'\d+', s)))

def move_brick_down(brick):
    return (brick[0], brick[1], brick[2] - 1, brick[3], brick[4], brick[5] - 1, brick[6])

def generate_brick_positions(brick):
    for x in range(brick[0], brick[3] + 1):
        for y in range(brick[1], brick[4] + 1):
            for z in range(brick[2], brick[5] + 1):
                yield (x, y, z)

# Parse input file and add index to each brick
bricks_with_index = [tuple(extract_integers(line) + [i]) for i, line in enumerate(open('22.txt').read().strip().split('\n'))]

occupied_positions = {}
fallen_bricks = []

# Process bricks in sorted order based on the third coordinate
for brick in sorted(bricks_with_index, key=lambda brick: brick[2]):
    while True:
        next_brick = move_brick_down(brick)
        # Check if the next position is valid and update the brick
        if not any(pos in occupied_positions for pos in generate_brick_positions(next_brick)) and next_brick[2] > 0:
            brick = next_brick
        else:
            # Mark the positions of the current brick as occupied and add it to the fallen list
            for pos in generate_brick_positions(brick):
                occupied_positions[pos] = brick
            fallen_bricks.append(brick)
            break

above = defaultdict(set)
below = defaultdict(set)

# Create relationships between bricks above and below
for brick in fallen_bricks:
    positions_in_current_brick = set(generate_brick_positions(brick))
    for pos in generate_brick_positions(move_brick_down(brick)):
        if pos in occupied_positions and pos not in positions_in_current_brick:
            above[occupied_positions[pos]].add(brick)
            below[brick].add(occupied_positions[pos])

# Function to find the number of bricks that would fall if a given brick disintegrated
def calculate_falling_bricks(disintegrated):
    falling_bricks = set()

    # Recursive function to find all falling bricks
    def find_falling_bricks(brick):
        if brick in falling_bricks:
            return
        falling_bricks.add(brick)
        for parent in above[brick]:
            if not len(below[parent] - falling_bricks):
                find_falling_bricks(parent)

    find_falling_bricks(disintegrated)
    return len(falling_bricks)

p1 = 0
p2 = 0

# Calculate the number of falling bricks for each fallen brick
for brick in fallen_bricks:
    would_fall = calculate_falling_bricks(brick)
    p1 += would_fall == 1
    p2 += would_fall - 1

# Print the results
print("Part 1:", p1)
print("Part 2:", p2)
