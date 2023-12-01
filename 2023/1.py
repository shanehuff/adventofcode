def extract_calibration_values(document):
    total_sum = 0
    for line in document:
        # Find the first and last digits in the line
        first_digit = int(next(char for char in line if char.isdigit()))
        last_digit = int(next(char for char in reversed(line) if char.isdigit()))

        # Combine the digits to form a two-digit number
        calibration_value = first_digit * 10 + last_digit

        # Add the calibration value to the total sum
        total_sum += calibration_value

    return total_sum


# Open the file and read its contents
with open('1.txt', 'r') as file:
    # Read lines from the file
    lines = file.readlines()

# Create an empty list to store the data
data_list = []

# Process each line and append it to the list
for line in lines:
    data_list.append(line.strip())  # Remove newline characters

# Example calibration document
calibration_document = data_list

# Calculate the sum of calibration values
result = extract_calibration_values(calibration_document)

# Print the result
print(result)
