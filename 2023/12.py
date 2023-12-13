lines = [line.strip() for line in open("12.txt").readlines()]

import functools

@functools.lru_cache(maxsize=None)
def solve(s, within_run, remain):
    if not s:
        if within_run is None and len(remain) == 0:
            return 1
        if len(remain) == 1 and within_run is not None and within_run == remain[0]:
            return 1
        return 0

    possible_more = 0
    for ch in s:
        if ch == '#' or ch == '?':
            possible_more += 1

    if within_run is not None and possible_more + within_run < sum(remain):
        return 0
    if within_run is None and possible_more < sum(remain):
        return 0
    if within_run is not None and len(remain) == 0:
        return 0

    poss = 0
    if s[0] == '.' and within_run is not None and within_run != remain[0]:
        return 0
    if s[0] == '.' and within_run is not None:
        poss += solve(s[1:], None, remain[1:])
    if s[0] == '?' and within_run is not None and within_run == remain[0]:
        poss += solve(s[1:], None, remain[1:])
    if (s[0] == '#' or s[0] == '?') and within_run is not None:
        poss += solve(s[1:], within_run + 1, remain)
    if (s[0] == '?' or s[0] == '#') and within_run is None:
        poss += solve(s[1:], 1, remain)
    if (s[0] == '?' or s[0] == '.') and within_run is None:
        poss += solve(s[1:], None, remain)

    return poss

p1 = 0
p2 = 0

for line in lines:
    s = line.split(" ")[0]
    v = tuple([int(x) for x in line.split(" ")[1].split(",")])
    p1 += solve(s, None, v)

    new_s = "?" + s * 5
    p2 += solve(new_s[1:], None, v * 5)

print(p1, p2)
