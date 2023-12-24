DAY = 24
from z3 import *
def parse(s):
    x,y,z = map(int, s.split(','))
    return (x,y,z)
def intersect(p1, v1, p2, v2):
    t1 = Real('t1')
    t2 = Real('t2')
    s = Solver()
    s.add(t1 >= 0)
    s.add(t2 >= 0)
    for i in range(2):
        s.add(p1[i] + t1*v1[i] == p2[i] + t2*v2[0])
        s.add(200000000000000 <= p1[i] + t1*v1[0])
        s.add(p1[i] + t1*v1[i] <= 400000000000000)
    return s.check() == sat
pts = []
for L in open('24.txt').read().splitlines():
    pts.append(tuple(map(parse, L.split(' @ '))))
# pt1
acc = 0
for i in range(len(pts)):
    for j in range(i+1, len(pts)):
        p1, v1 = pts[i]
        p2, v2 = pts[j]
        if intersect(p1,v1, p2,v2):
            acc += 1
print('part 1:', acc)
# pt2
P = [Real(f'P{i}') for i in range(3)]
PV = [Real(f'PV{i}') for i in range(3)]
s = Solver()
for i in range(len(pts)):
    t = Real(f't{i}')
    p,v = pts[i]
    for c in range(3):
        s.add(P[c] + t*PV[c] == p[c] + t*v[c])
if s.check() == sat:
    m = s.model()
    print('part 2:', sum(int(str(m.evaluate(v))) for v in P))
else:
    print('failed to solve')
