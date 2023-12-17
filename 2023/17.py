import sys
from collections import defaultdict, deque

def read_input_matrix():
    # Read input matrix from stdin
    input_lines = sys.stdin.read().strip().split('\n')
    rows = len(input_lines)
    cols = len(input_lines[0])
    matrix = []
    for row in range(rows):
        matrix.append([])
        for col in range(cols):
            matrix[row].append(int(input_lines[row][col]))
    return matrix, rows, cols

def main():
    # Read input matrix and dimensions
    matrix, rows, cols = read_input_matrix()

    # Direction mappings
    go_directions = {'U': 'LR', 'D': 'LR', 'L': 'UD', 'R': 'UD'}
    opposite_directions = {'U': 'UD', 'D': 'UD', 'L': 'LR', 'R': 'LR'}
    delta_moves = {'U': (-1, 0), 'D': (1, 0), 'L': (0, -1), 'R': (0, 1)}

    # Iterate over ranges
    for min_val, max_val in [(1, 3), (4, 10)]:
        best_scores = defaultdict(lambda: 1000000000)
        seen_positions = set()
        queue = deque()
        queue.appendleft((0, 0, 'L', 0))
        queue.appendleft((0, 0, 'D', 0))

        while queue:
            current_row, current_col, direction, current_score = queue.pop()

            opposite_direction = opposite_directions[direction]
            if (current_row, current_col, opposite_direction) in seen_positions:
                continue

            previous_score = best_scores[current_row, current_col, opposite_direction]
            if previous_score <= current_score:
                continue

            best_scores[(current_row, current_col, opposite_direction)] = current_score

            for new_direction in go_directions[direction]:
                delta_score = 0
                delta_row, delta_col = delta_moves[new_direction]
                new_row, new_col = current_row, current_col

                for step in range(1, max_val + 1):
                    new_row += delta_row
                    new_col += delta_col
                    if 0 <= new_row < rows and 0 <= new_col < cols:
                        delta_score += matrix[new_row][new_col]
                        if step >= min_val:
                            queue.appendleft((new_row, new_col, new_direction, current_score + delta_score))

        # Print the minimum score for reaching the bottom-right cell in either UD or LR directions
        print(min([best_scores[rows - 1, cols - 1, d] for d in ['UD', 'LR']]))

if __name__ == "__main__":
    main()
