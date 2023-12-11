import itertools

def get_distance_sum(galaxies, multiplier):
    distances = []
    for g1, g2 in itertools.combinations(galaxies, 2):
        d = abs(g1[0] - g2[0]) + abs(g1[1] - g2[1])
        r1, r2 = sorted([g1[0], g2[0]])
        for empty_row in empty_rows:
            if r1 < empty_row < r2:
                d += (multiplier - 1)
        c1, c2 = sorted([g1[1], g2[1]])
        for empty_col in empty_cols:
            if c1 < empty_col < c2:
                d += (multiplier - 1)
        distances.append(d)
    return sum(distances)

def read_input(filename):
    with open(filename) as file:
        return [line.strip() for line in file if line.strip()]

# Retrieve data
data = read_input('11.txt')
lines = data

# Find empty rows
empty_rows = [r for r, row in enumerate(lines) if '#' not in row]

# Transpose the grid
lines = list(map(list, zip(*lines)))
# Find empty columns
empty_cols = [c for c, col in enumerate(lines) if '#' not in col]
# Transpose the grid back
lines = list(map(list, zip(*lines)))

# Extract coordinates of '#'
galaxies = [(r, c) for r, row in enumerate(lines) for c, ch in enumerate(row) if ch == '#']

# Calculate and print distance sums with different multipliers
print(get_distance_sum(galaxies, 2))
print(get_distance_sum(galaxies, 1_000_000))
