import sys
import re
from copy import deepcopy
from math import gcd
from collections import defaultdict, Counter, deque
import heapq
import math

# Read input from file '20.txt' and parse it
D = open('20.txt').read().strip()
L = D.split('\n')
G = [[c for c in row] for row in L]
R = len(G)
C = len(G[0])

# Function to calculate the least common multiple of a list of numbers
def lcm(xs):
    ans = 1
    for x in xs:
        ans = (ans * x) // math.gcd(x, ans)
    return ans

# Dictionary to store the type of each variable (TYP = Type)
TYP = {}

# Dictionary to store the relationships between variables
R = {}
for line in L:
    src, dest = line.split('->')
    src = src.strip()
    dest = dest.strip()
    dest = dest.split(', ')
    R[src] = dest
    TYP[src[1:]] = src[0]

# Function to adjust the type of a variable
def adjust(y):
    if y in TYP:
        return TYP[y] + y
    else:
        return y

# Dictionary to store relationships between variables (FROM) and inverse relationships (INV)
FROM = {}
INV = defaultdict(list)
for x, ys in R.items():
    R[x] = [adjust(y) for y in ys]
    for y in R[x]:
        if y[0] == '&':
            if y not in FROM:
                FROM[y] = {}
            FROM[y][x] = 'lo'
        INV[y].append(x)

# Assertions to check specific conditions
assert len(INV['rx']) == 1
assert INV['rx'][0][0] == '&'
WATCH = INV[INV['rx'][0]]

lo = 0
hi = 0
Q = deque()
ON = set()
PREV = {}
COUNT = defaultdict(int)
TO_LCM = []

# Loop over a range of timestamps
for t in range(1, 10**8):
    Q.append(('broadcaster', 'button', 'lo'))

    while Q:
        x, from_, typ = Q.popleft()

        # Handling for 'lo' type
        if typ == 'lo':
            # Check for a specific condition and update information
            if x in PREV and COUNT[x] == 2 and x in WATCH:
                TO_LCM.append(t - PREV[x])
            PREV[x] = t
            COUNT[x] += 1

        # Check if all necessary conditions are met for calculating LCM
        if len(TO_LCM) == len(WATCH):
            print(lcm(TO_LCM))
            sys.exit(0)

        # Handling for 'rx' and 'lo' type
        if x == 'rx' and typ == 'lo':
            # won't happen; takes too long
            print(t + 1)

        # Update counts for 'lo' and 'hi' types
        if typ == 'lo':
            lo += 1
        else:
            hi += 1

        # Continue if variable 'x' does not have relationships
        if x not in R:
            continue

        # Handling for 'broadcaster' type
        if x == 'broadcaster':
            for y in R[x]:
                Q.append((y, x, typ))
        # Handling for '%' type
        elif x[0] == '%':
            if typ == 'hi':
                continue
            else:
                # Update information based on the type
                if x not in ON:
                    ON.add(x)
                    new_typ = 'hi'
                else:
                    ON.discard(x)
                    new_typ = 'lo'
                for y in R[x]:
                    Q.append((y, x, new_typ))
        # Handling for '&' type
        elif x[0] == '&':
            FROM[x][from_] = typ
            new_typ = ('lo' if all(y == 'hi' for y in FROM[x].values()) else 'hi')
            for y in R[x]:
                Q.append((y, x, new_typ))
        else:
            assert False, x

    # Output the result after a certain timestamp
    if t == 1000:
        print(lo * hi)
