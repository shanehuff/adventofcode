import sys
import re
from copy import deepcopy
from math import gcd
from collections import defaultdict, Counter, deque

# Read input data from file
input_data = open('13.txt').read().strip()
lines = input_data.split('\n')
original_grids = [[char for char in row] for row in lines]

# Iterate over symmetry options (part1 and part2)
for is_part2 in [False, True]:
    total_symmetry_score = 0

    # Iterate over each grid in the input
    for grid_data in input_data.split('\n\n'):
        current_grid = [[char for char in row] for row in grid_data.split('\n')]
        rows = len(current_grid)
        cols = len(current_grid[0])

        # Check for vertical symmetry
        for col in range(cols - 1):
            badness = 0
            for delta_col in range(cols):
                left_col = col - delta_col
                right_col = col + 1 + delta_col

                if 0 <= left_col < right_col < cols:
                    for row in range(rows):
                        if current_grid[row][left_col] != current_grid[row][right_col]:
                            badness += 1

            if badness == (1 if is_part2 else 0):
                total_symmetry_score += col + 1

        # Check for horizontal symmetry
        for row in range(rows - 1):
            badness = 0
            for delta_row in range(rows):
                up_row = row - delta_row
                down_row = row + 1 + delta_row

                if 0 <= up_row < down_row < rows:
                    for col in range(cols):
                        if current_grid[up_row][col] != current_grid[down_row][col]:
                            badness += 1

            if badness == (1 if is_part2 else 0):
                total_symmetry_score += 100 * (row + 1)

    # Print the total symmetry score for the current part
    print(total_symmetry_score)
