import sys

MAX_VALUES = {
    "r": 12,
    "g": 13,
    "b": 14
}

def is_possible(subset):
    for pair in subset.split(","):
        count, color = pair.strip().split(" ")
        count = int(count)
        if count > MAX_VALUES[color[0]]:
            return False
    return True

total_sum = 0

for line in sys.stdin:
    line = line.strip()
    game, subsets = line.split(':')
    game = int(game[5:])

    is_game_possible = all([is_possible(subset.strip()) for subset in subsets.split(";")])
    total_sum += game if is_game_possible else 0

print(total_sum)

def calculate_max_counts(subset):
    max_counts = {"r": 0, "g": 0, "b": 0}

    for pair in subset.split(","):
        count, color = pair.strip().split(" ")
        count = int(count)
        color = color[0]
        max_counts[color] = max(max_counts[color], count)

    return max_counts

total_product = 0

for line in sys.stdin:
    line = line.strip()
    game, subsets = line.split(':')
    game = int(game[5:])

    subset_results = [calculate_max_counts(subset.strip()) for subset in subsets.split(";")]

    max_r = max(subset["r"] for subset in subset_results)
    max_g = max(subset["g"] for subset in subset_results)
    max_b = max(subset["b"] for subset in subset_results)

    total_product += max_r * max_g * max_b

print(total_product)
