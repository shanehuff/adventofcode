import sys
import re
from copy import deepcopy
from math import gcd
from collections import defaultdict, Counter, deque

# Read input from file and initialize grid
file_content = open('16.txt').read().strip()
grid_lines = file_content.split('\n')
grid = [[cell for cell in row] for row in grid_lines]

num_rows = len(grid)
num_columns = len(grid[0])

# Define movement directions: up, right, down, left
row_moves = [-1, 0, 1, 0]
column_moves = [0, 1, 0, -1]

# Function to perform a step in the given direction
def move(row, column, direction):
    return (row + row_moves[direction], column + column_moves[direction], direction)

# Function to calculate the score of a given starting position and direction
def calculate_score(start_row, start_column, start_direction):
    positions = [(start_row, start_column, start_direction)]  # Initialize positions
    visited_positions = set()  # Set to store visited positions
    visited_positions_with_direction = set()  # Set to store visited positions with direction
    while True:
        new_positions = []
        if not positions:
            break
        for (row, column, direction) in positions:
            # Check if the position is within the grid
            if 0 <= row < num_rows and 0 <= column < num_columns:
                visited_positions.add((row, column))
                if (row, column, direction) in visited_positions_with_direction:
                    continue
                visited_positions_with_direction.add((row, column, direction))
                cell = grid[row][column]
                # Move forward in the current direction if the cell is empty
                if cell == '.':
                    new_positions.append(move(row, column, direction))
                # Change direction based on the cell type
                elif cell == '/':
                    new_positions.append(move(row, column, {0: 1, 1: 0, 2: 3, 3: 2}[direction]))
                elif cell == '\\':
                    new_positions.append(move(row, column, {0: 3, 1: 2, 2: 1, 3: 0}[direction]))
                elif cell == '|':
                    # Move vertically if the current direction is up or down, otherwise, move up and down
                    if direction in [0, 2]:
                        new_positions.append(move(row, column, direction))
                    else:
                        new_positions.append(move(row, column, 0))
                        new_positions.append(move(row, column, 2))
                elif cell == '-':
                    # Move horizontally if the current direction is left or right, otherwise, move left and right
                    if direction in [1, 3]:
                        new_positions.append(move(row, column, direction))
                    else:
                        new_positions.append(move(row, column, 1))
                        new_positions.append(move(row, column, 3))
                else:
                    assert False  # Should not reach here
        positions = new_positions
    return len(visited_positions)

# Calculate and print the score for the specified starting position and direction
print(calculate_score(0, 0, 1))

# Find the maximum score by trying different starting positions and directions
maximum_score = 0
for row in range(num_rows):
    maximum_score = max(maximum_score, calculate_score(row, 0, 1))
    maximum_score = max(maximum_score, calculate_score(row, num_columns - 1, 3))
for column in range(num_columns):
    maximum_score = max(maximum_score, calculate_score(0, column, 2))
    maximum_score = max(maximum_score, calculate_score(num_rows - 1, column, 0))
print(maximum_score)
