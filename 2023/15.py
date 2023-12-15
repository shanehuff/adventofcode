# Read and process the content of the file
file_content = open('15.txt').read().strip().split(',')

# Initialize variables for hash calculations
hash_value = 0
hash_multiplier = 17
hash_modulo = 256
total_p1 = 0
total_p2 = 0

# Function to calculate a hash value for a given string
def calculate_hash(value):
    result = 0
    for char in value:
        result += ord(char)
        result *= hash_multiplier
        result %= hash_modulo
    return result

# Lists to store buckets and their lengths
buckets = [[] for _ in range(hash_modulo)]
bucket_lengths = [{} for _ in range(hash_modulo)]

# Process each line in the file content
for index, line in enumerate(file_content):
    # Update total_p1 with the hash of the line
    total_p1 += calculate_hash(line)
    
    # Extract label and calculate hash for label
    label = line.split("=")[0].split("-")[0]
    hash_label = calculate_hash(label)

    # Handle cases with '-' in the line
    if "-" in line:
        if label in buckets[hash_label]:
            buckets[hash_label].remove(label)

    # Handle cases with '=' in the line
    if "=" in line:
        if label not in buckets[hash_label]:
            buckets[hash_label].append(label)
        bucket_lengths[hash_label][label] = int(line.split("=")[1])

# Calculate total_p2 using buckets and their lengths
for box, labels in enumerate(buckets):
    for i, label in enumerate(labels):
        total_p2 += (box + 1) * (i + 1) * bucket_lengths[box][label]

# Print the final results
print("Total P1:", total_p1)
print("Total P2:", total_p2)
