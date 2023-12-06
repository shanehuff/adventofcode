#!/usr/bin/env python3

# Define constants for the day number and description
DAY_NUM = 6
DAY_DESC = 'Day 6: Wait For It'

def calc(log, values, mode):
    # Extract times and distances from input values
    if mode == 1:
        times = [int(x) for x in values[0].split(":")[1].split(" ") if len(x)]
        distances = [int(x) for x in values[1].split(":")[1].split(" ") if len(x)]
    else:
        times = [int(values[0].split(":")[1].replace(" ", ""))]
        distances = [int(values[1].split(":")[1].replace(" ", ""))]

    # Initialize result variable
    ret = 1

    # Iterate over pairs of time and distance
    for time, distance in zip(times, distances):
        wins = 0
        # Check possible wins for each second within the given time
        for i in range(time):
            if i * (time - i) > distance:
                wins += 1
        ret *= wins

    return ret

def run(log, values):
    # Run the calculation for both modes and log the results
    log(calc(log, values, 1))
    log(calc(log, values, 2))

if __name__ == "__main__":
    import sys, os

    # Helper function to find the input file
    def find_input_file():
        for fn in sys.argv[1:] + ["6.txt"]:
            for dn in [[], ["Puzzles"], ["..", "Puzzles"]]:
                cur = os.path.join(*(dn + [fn]))
                if os.path.isfile(cur):
                    return cur

    # Find and load the input file
    fn = find_input_file()
    if fn is None:
        print("Unable to find input file!\nSpecify filename on command line")
        exit(1)

    # Display the input file being used
    print(f"Using '{fn}' as input file:")

    # Read the input file and run the program
    with open(fn) as f:
        values = [x.strip("\r\n") for x in f.readlines()]

    print(f"Running {DAY_DESC}:")
    run(print, values)
