#!/usr/bin/python3
import sys
import collections

infile = sys.argv[1] if len(sys.argv)>1 else '23.in'
data = open(infile).read().strip()

elves = set(
    (r, c)
    for r, line in enumerate(data.splitlines())
    for c, x in enumerate(line)
    if x == '#'
)

elves

directions = [
    [(-1, 0), (-1, 1), (-1, -1)],
    [(1, 0), (1, 1), (1, -1)],
    [(0, -1), (1, -1), (-1, -1)],
    [(0, 1), (1, 1), (-1, 1)],
]

adjacent = [
    (-1, 0),
    (-1, 1),
    (-1, -1),
    (1, 0),
    (1, 1),
    (1, -1),
    (0, -1),
    (0, 1),
]

rounds = 0
while True:
    rounds += 1
    moves = {}
    for r, c in elves:
        if all((r + a[0], c + a[1]) not in elves for a in adjacent):
            continue
        for ds in directions:
            if all((r + d[0], c + d[1]) not in elves for d in ds):
                moves[(r, c)] = (r + ds[0][0], c + ds[0][1])
                break
    if not moves:
        break
    counts = collections.Counter(moves.values())
    for current, new in moves.items():
        if counts[new] > 1:
            continue
        elves.remove(current)
        elves.add(new)
    if rounds == 10:
        break
    directions = directions[1:] + [directions[0]]

answer = (max(r for r, _ in elves) - min(r for r, _ in elves) + 1) * (max(c for _, c in elves) - min(c for _, c in elves) + 1) - len(elves)
print(answer)