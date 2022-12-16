#!/usr/bin/python3
import sys

Fs = {}
Vs = {}
Os = {}

infile = sys.argv[1] if len(sys.argv)>1 else 'input.txt'
IN = open(infile).read().strip()

for ln in IN.split("\n"):
   A, B = ln.split("; ")
   _, name, *_, rate = A.split()
   rate = int(rate[5:])
   valves = B.split("valve")[1]
   if valves.startswith("s "):
       valves = valves[2:]
   valves = valves.strip().split(", ")

   Fs[name] = rate
   Vs[name] = valves
   Os[name] = False

_seen = {}
m = 0
def f(t, pos, flow):
    global m, Vs, Os, _seen

    if _seen.get((t, pos), -1) >= sum(flow):
        return
    _seen[t, pos] = sum(flow)

    
    if t == 30:
        m = max(m, sum(flow))
        print(m)
        return

#      Open valve here?
    for k in (0, 1):
        if k == 0:
            if Os[pos] or Fs[pos] <= 0:
                continue

            Os[pos] = True
            j = sum(Fs[k] for k, v in Os.items() if v)
            f(
                t + 1,
                pos,
                flow + [ j ]
            )
            Os[pos] = False
        else:
            j = sum(Fs[k] for k, v in Os.items() if v)
            for v in Vs[pos]:
                f(
                    t + 1,
                    v if v is not None else pos,
                    flow + [ j ]
                )

f(1, "AA", [ 0 ])
