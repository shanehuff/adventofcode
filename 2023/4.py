import sys
import re
from collections import defaultdict

# Read input from file and split into lines
input_data = open('4.txt').read().strip()
lines = input_data.split('\n')

total_points = 0
occurrences_dict = defaultdict(int)

for i, line in enumerate(lines):
    occurrences_dict[i] += 1
    first_part, rest_part = line.split('|')
    identifier, card_numbers = first_part.split(':')

    card_values = [int(x) for x in card_numbers.split()]
    rest_values = [int(x) for x in rest_part.split()]

    common_values_count = len(set(card_values) & set(rest_values))

    if common_values_count > 0:
        total_points += 2 ** (common_values_count - 1)

    for j in range(common_values_count):
        occurrences_dict[i + 1 + j] += occurrences_dict[i]

print(total_points)
print(sum(occurrences_dict.values()))
