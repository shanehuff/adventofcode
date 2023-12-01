import re

def calculate_calibration_values(lines):
    total_calibration = 0

    for line in lines:
        # Extract all digits from the line
        digits = re.findall(r'\d', line)

        # If there are at least two digits, add the first and last digits to the total
        if len(digits) >= 2:
            first_digit = int(digits[0])
            last_digit = int(digits[-1])
            total_calibration += first_digit * 10 + last_digit

    return total_calibration

# Read data from
