import sys
import re
from math import gcd
from collections import defaultdict, Counter

# Read input from file and split into lines
data = open('8.txt').read().strip()
lines = data.split('\n')

# Function to calculate the least common multiple
def lcm(numbers):
    result = 1
    for num in numbers:
        result = (num * result) // gcd(num, result)
    return result

# Initialize dictionaries for left and right rules
rules = {'L': {}, 'R': {}}

# Split the data into steps and rules
steps, rule = data.split('\n\n')

# Process each rule and populate the dictionaries
for line in rule.split('\n'):
    state, transition = line.split('=')
    state = state.strip()
    left, right = transition.split(',')
    left = left.strip()[1:].strip()
    right = right[:-1].strip()
    rules['L'][state] = left
    rules['R'][state] = right

# Function to solve the problem
def find_least_common_multiple(part2):
    positions = []

    # Find initial positions based on the condition
    for state in rules['L']:
        if state.endswith('A' if part2 else 'AAA'):
            positions.append(state)

    time_steps = {}  # Dictionary to store the time steps for each position
    time = 0

    while True:
        new_positions = []

        # Update positions based on rules
        for i, pos in enumerate(positions):
            pos = rules[steps[time % len(steps)]][pos]

            # Check if the position ends with 'Z'
            if pos.endswith('Z'):
                time_steps[i] = time + 1

                # Use Chinese Remainder Theorem to find the solution
                if len(time_steps) == len(positions):
                    return lcm(time_steps.values())

            new_positions.append(pos)

        positions = new_positions
        time += 1

    # Assertion in case the loop doesn't break
    assert False

# Print the results for both part1 and part2
print(find_least_common_multiple(False))
print(find_least_common_multiple(True))
