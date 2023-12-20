import re

# Read the content of the file and split it into sections
sections = [section for section in open('19.txt').read().strip().split('\n\n')]
workflow_section, parts_section = sections

# Define a function to extract integers from a string
def extract_integers(s):
    return list(map(int, re.findall(r'\d+', s)))

# Convert the 'parts' section into a list of lists of integers
parts = [extract_integers(line) for line in parts_section.split("\n")]

# Convert the 'workflow' section into a dictionary
workflow_conditions = {line.split("{")[0]: line.split("{")[1][:-1] for line in workflow_section.split("\n")}

# Define a recursive function to evaluate conditions in the workflow
def evaluate_condition(part, condition_key):
    current_condition = workflow_conditions[condition_key]
    x, m, a, s = part
    for condition in current_condition.split(","):
        if condition == "R":
            return False
        if condition == "A":
            return True
        if ":" not in condition:
            return evaluate_condition(part, condition)
        condition_check, result_condition = condition.split(":")
        if eval(condition_check):
            if result_condition == "R":
                return False
            if result_condition == "A":
                return True
            return evaluate_condition(part, result_condition)
    raise Exception(current_condition)

# Initialize a variable to store the sum of certain parts
sum_of_selected_parts = 0

# Loop through parts and update the sum based on the evaluation of conditions
for part in parts:
    if evaluate_condition(part, 'in'):
        sum_of_selected_parts += sum(part)
print(sum_of_selected_parts)

# Define a function to modify ranges based on conditions
def modify_ranges(ch, gt, val, ranges):
    index = 'xmas'.index(ch)
    modified_ranges = []
    for rng in ranges:
        rng = list(rng)
        lo, hi = rng[index]
        if gt:
            lo = max(lo, val + 1)
        else:
            hi = min(hi, val - 1)
        if lo <= hi:
            rng[index] = (lo, hi)
            modified_ranges.append(tuple(rng))
    return modified_ranges

# Define a function to get acceptance ranges for the outer workflow
def get_outer_acceptance_ranges(work_key):
    return get_inner_acceptance_ranges(workflow_conditions[work_key].split(","))

# Define a recursive function to get acceptance ranges for the inner workflow
def get_inner_acceptance_ranges(w):
    condition = w[0]
    if condition == "R":
        return []
    if condition == "A":
        return [((1, 4000), (1, 4000), (1, 4000), (1, 4000))]
    if ":" not in condition:
        return get_outer_acceptance_ranges(condition)
    condition_check, gt = condition.split(":")[0], ">" in condition
    ch, val = condition_check[0], int(condition_check[2:])
    val_inverted = val + 1 if gt else val - 1
    true_ranges = modify_ranges(ch, gt, val, get_inner_acceptance_ranges([condition.split(":")[1]]))
    false_ranges = modify_ranges(ch, not gt, val_inverted, get_inner_acceptance_ranges(w[1:]))
    return true_ranges + false_ranges

# Initialize a variable to store the final product of ranges
final_product_of_ranges = 0

# Loop through outer acceptance ranges and calculate the product of each range
for rng in get_outer_acceptance_ranges('in'):
    product = 1
    for lo, hi in rng:
        product *= hi - lo + 1
    final_product_of_ranges += product
print(final_product_of_ranges)
