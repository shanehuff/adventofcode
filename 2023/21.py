import sys
import re
from copy import deepcopy
from math import gcd
from collections import defaultdict, Counter, deque
import heapq

maze_data = open('21.txt').read().strip()
maze_lines = maze_data.split('\n')
maze_grid = [[cell for cell in row] for row in maze_lines]
rows = len(maze_grid)
cols = len(maze_grid[0])

for r in range(rows):
    for c in range(cols):
        if maze_grid[r][c] == 'S':
            start_row, start_col = r, c

def find_distances(row, col):
    distances = {}
    queue = deque([(0, 0, start_row, start_col, 0)])

    while queue:
        tr, tc, r, c, d = queue.popleft()

        if r < 0:
            tr -= 1
            r += rows
        if r >= rows:
            tr += 1
            r -= rows
        if c < 0:
            tc -= 1
            c += cols
        if c >= cols:
            tc += 1
            c -= cols

        if not (0 <= r < rows and 0 <= c < cols and maze_grid[r][c] != '#'):
            continue

        if (tr, tc, r, c) in distances:
            continue

        if abs(tr) > 4 or abs(tc) > 4:
            continue

        distances[(tr, tc, r, c)] = d

        for dr, dc in [[-1, 0], [0, 1], [1, 0], [0, -1]]:
            queue.append((tr, tc, r + dr, c + dc, d + 1))

    return distances

distances = find_distances(start_row, start_col)

solutions_cache = {}

def count_paths(distance, value, total_distance):
    amount = (total_distance - distance) // rows

    if (distance, value, total_distance) in solutions_cache:
        return solutions_cache[(distance, value, total_distance)]

    result = 0

    for x in range(1, amount + 1):
        if distance + rows * x <= total_distance and (distance + rows * x) % 2 == (total_distance % 2):
            result += ((x + 1) if value == 2 else 1)

    solutions_cache[(distance, value, total_distance)] = result
    return result

def solve_puzzle(part1):
    total_distance = 64 if part1 else 26501365
    answer = 0

    for r in range(rows):
        for c in range(cols):
            if (0, 0, r, c) in distances:
                distance_function = distances[(0, 0, r, c)]

                def fast(tr, tc):
                    result = 0
                    bound = 3

                    if tr > bound:
                        result += rows * (abs(tr) - bound)
                        tr = bound
                    if tr < -bound:
                        result += rows * (abs(tr) - bound)
                        tr = -bound
                    if tc > bound:
                        result += cols * (abs(tc) - bound)
                        tc = bound
                    if tc < -bound:
                        result += cols * (abs(tc) - bound)
                        tc = -bound

                    result += distance_function[(tr, tc, r, c)]
                    return result

                optimal_values = [-3, -2, -1, 0, 1, 2, 3]

                for tr in optimal_values:
                    for tc in optimal_values:
                        if part1 and (tr != 0 or tc != 0):
                            continue

                        distance = distance_function[(tr, tc, r, c)]

                        if distance % 2 == total_distance % 2 and distance <= total_distance:
                            answer += 1

                        if tr in [min(optimal_values), max(optimal_values)] and tc in [min(optimal_values),
                                                                                      max(optimal_values)]:
                            answer += count_paths(distance, 2, total_distance)
                        elif tr in [min(optimal_values), max(optimal_values)] or tc in [min(optimal_values),
                                                                                        max(optimal_values)]:
                            answer += count_paths(distance, 1, total_distance)

    return answer

print(solve_puzzle(True))
print(solve_puzzle(False))
