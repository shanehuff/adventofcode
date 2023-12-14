from itertools import count

def read_input(file_path):
    with open(file_path, "r") as file:
        return file.read().strip()

def tilt_north(i, j, grid):
    while i > 0 and grid[i - 1][j] == ".":
        grid[i][j] = "."
        grid[i - 1][j] = "O"
        i -= 1
    return i, j

def tilt_west(i, j, grid):
    while j > 0 and grid[i][j - 1] == ".":
        grid[i][j] = "."
        grid[i][j - 1] = "O"
        j -= 1
    return i, j

def tilt_south(i, j, grid):
    while i < len(grid) - 1 and grid[i + 1][j] == ".":
        grid[i][j] = "."
        grid[i + 1][j] = "O"
        i += 1
    return i, j

def tilt_east(i, j, grid):
    while j < len(grid[0]) - 1 and grid[i][j + 1] == ".":
        grid[i][j] = "."
        grid[i][j + 1] = "O"
        j += 1
    return i, j

def calc_load(grid):
    return sum(i * row.count("O") for i, row in enumerate(grid[::-1], start=1))

def cycle(rocks, grid):
    keys = [lambda x: x[1], lambda x: -x[0], lambda x: -x[1], lambda x: x[0]]
    for k, f in enumerate((tilt_north, tilt_west, tilt_south, tilt_east)):
        nrocks = [f(i, j, grid) for i, j in rocks]
        rocks = sorted(nrocks, key=keys[k % 4])
    return rocks, grid

def find_cycle(rocks, grid):
    seen = {"\n".join("".join(row) for row in grid): 0}
    for i in count(start=1):
        rocks, grid = cycle(rocks, grid)
        cur = "\n".join("".join(row) for row in grid)
        if cur in seen:
            return seen[cur], i - seen[cur], seen
        seen[cur] = i

def parse_grid(data):
    return [list(row) for row in data.split("\n")]

def get_rocks(grid):
    return [(i, j) for j in range(len(grid[0])) for i in range(len(grid)) if grid[i][j] == "O"]

def part_one():
    grid = parse_grid(read_input("14.txt"))
    rocks = get_rocks(grid)
    for i, j in rocks:
        tilt_north(i, j, grid)
    return calc_load(grid)

def part_two():
    grid = parse_grid(read_input("14.txt"))
    rocks = get_rocks(grid)
    start, length, seen = find_cycle(rocks, grid)
    rseen = {v: k for k, v in seen.items()}
    res = rseen[start + (1000000000 - start) % length]
    return calc_load(res.split("\n"))

print(f"Part 1: {part_one()}")  # 112773
print(f"Part 2: {part_two()}")  # 98894
