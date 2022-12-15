#!/usr/bin/python3
import sys

infile = sys.argv[1] if len(sys.argv)>1 else 'input.txt'
IN = open(infile).read().strip()
Y = 2000000
zones = []
bcxs = set()

for ln in IN.split("\n"):
    sx, sy, bx, by = ints(ln)
    man = abs(sx - bx) + abs(sy - by) - 1
    if by == Y:
        bcxs.add(bx)
    man = man - abs(Y - sy) + 1
    if man < 0:
        continue
    reachx, reachy = sx - man, sx + man
    zones.append((reachx, reachy))

S = set()
for x, y in zones:
    print(x, y)
    S = S.union(set(range(x, y + 1)))
print(len(S - bcxs))
