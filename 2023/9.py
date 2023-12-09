#!/usr/bin/env python3

def calculate_total(log, values, mode):
    """
    Calculate the total based on the specified mode.

    Parameters:
    - log: Logging function for printing.
    - values: List of input values.
    - mode: Mode of calculation (1 or 2).

    Returns:
    - Total calculated based on the specified mode.
    """
    total = 0

    for row in values:
        # Convert each row of space-separated integers into a list of lists
        row_data = [[int(x) for x in row.split(" ")]]

        # Continue to append rows until the last row is all zeros
        while tuple(row_data[-1]) != tuple(0 for _ in row_data[-1]):
            last = row_data[-1]
            row_data.append([last[x + 1] - last[x] for x in range(len(last) - 1)])

        if mode == 1:
            # Mode 1: Accumulate values from the bottom to the top
            row_data[-1].append(0)
            for i in range(len(row_data) - 2, -1, -1):
                row_data[i].append(row_data[i + 1][-1] + row_data[i][-1])
            total += row_data[0][-1]
        else:
            # Mode 2: Accumulate values from the top to the bottom
            row_data[-1].insert(0, 0)
            for i in range(len(row_data) - 2, -1, -1):
                row_data[i].insert(0, row_data[i][0] - row_data[i + 1][0])
            total += row_data[0][0]

    return total

def run(log, values):
    """
    Run the script with the given logging function and input values.

    Parameters:
    - log: Logging function for printing.
    - values: List of input values.
    """
    log(calculate_total(log, values, 1))
    log(calculate_total(log, values, 2))

if __name__ == "__main__":
    import sys, os

    def find_input_file():
        for fn in sys.argv[1:] + ["9.txt"]:
            for dn in [[], ["Puzzles"], ["..", "Puzzles"]]:
                cur = os.path.join(*(dn + [fn]))
                if os.path.isfile(cur): return cur

    # Find and use the input file specified in the command line arguments or use the default "9.txt"
    input_file = find_input_file()
    if input_file is None: print("Unable to find input file!\nSpecify filename on command line"); exit(1)
    print(f"Using '{input_file}' as input file:")

    # Read the input values from the file
    with open(input_file) as f:
        input_values = [x.strip("\r\n") for x in f.readlines()]

    # Run the script with the print function for logging
    run(print, input_values)
