import sys

# Read input lines and remove leading/trailing whitespaces
lines = [l.strip() for l in sys.stdin]

# Extract the initial list of integers from the first line
current = [int(x) for x in lines[0][7:].split(" ")]
nxt = []

# Iterate through lines starting from the third line
for line in lines[2:]:
    # Check for an empty line and update the current list
    if len(line) == 0:
        current = nxt + current
        nxt = []
        continue

    # Skip lines containing ':'
    if ':' in line:
        continue

    # Extract parameters from the current line
    dstart, sstart, size = [int(x.strip()) for x in line.split(" ")]

    # Update the 'nxt' list based on the conditions
    aux = []
    for v in current:
        if v >= sstart and v < sstart + size:
            nxt.append(v - sstart + dstart)
        else:
            aux.append(v)
    current = aux

# Update the 'current' list with the remaining 'nxt' elements
current = nxt + current

# Print the minimum value in the 'current' list
print(min(current))

import sys

# Read input lines and remove leading/trailing whitespaces
lines = [l.strip() for l in sys.stdin]

# Extract initial range pairs from the first line
aux = [int(x) for x in lines[0][7:].split(" ")]
current = [(aux[i], aux[i] + aux[i + 1] - 1) for i in range(0, len(aux), 2)]
print(current)

# Initialize the 'nxt' list and index variable
nxt = []
i = 2

# Iterate through lines starting from the third line
for line in lines[2:]:
    # Check for an empty line and update the current list
    if len(line) == 0:
        current = nxt + current
        nxt = []
        continue

    # Skip lines containing ':'
    if ':' in line:
        continue

    # Extract parameters from the current line
    dstart, sstart, size = [int(x.strip()) for x in line.split(" ")]
    send = sstart + size
    diff = dstart - sstart

    # Initialize the 'aux' list for storing updated range pairs
    aux = []

    # Iterate through the current range pairs
    for a, b in current:
        # Cases for different range relationships
        if b < sstart or a >= send:
            aux.append((a, b))
            continue
        elif a < sstart and b < send:
            aux.append((a, sstart - 1))
            nxt.append((sstart + diff, b + diff))
        elif a < send and b >= send:
            aux.append((a, send - 1))
            nxt.append((send + diff, b + diff))
        elif a >= sstart and b < send:
            nxt.append((a + diff, b + diff))
        elif a < sstart and b >= send:
            aux.append((a, sstart - 1))
            aux.append((send, b))
            nxt.append((sstart + diff, send + diff))
        else:
            print(sstart, send, a, b)
            raise Exception("Unknown case")

    # Update the 'current' list with the 'aux' elements
    current = aux

# Update the 'current' list with the remaining 'nxt' elements
current = nxt + current

# Print the minimum value from the updated 'current' list
print(min(current)[0])
