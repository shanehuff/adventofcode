
_seen = {}
m = 0
def f(t, pos1, pos2, flow):
    global m, Vs, Os, _seen, _seen2

    if _seen.get((t, pos1, pos2), -1) >= sum(flow):
        return
    _seen[t, pos1, pos2] = sum(flow)

    if t == 26:
        if sum(flow) > m:
            m = sum(flow)
            print(m, flow)
        return

    # all open? just stay put...
    if all(v for k, v in Os.items() if Fs[k] > 0):
        tf = sum(Fs[k] for k, v in Os.items() if v)
        f(t + 1, pos1, pos2, flow + [tf])
        return

    # possible options for us...
    for k in (0, 1):
        if k == 0:
            if Os[pos1] or Fs[pos1] <= 0:
                continue

            Os[pos1] = True

            for k2 in (0, 1):
                if k2 == 0:
                    if Os[pos2] or Fs[pos2] <= 0:
                        continue

                    Os[pos2] = True
                    j = sum(Fs[k] for k, v in Os.items() if v)
                    f(
                        t + 1,
                        pos1,
                        pos2,
                        flow + [ j ]
                    )
                    Os[pos2] = False
                else:
                    j = sum(Fs[k] for k, v in Os.items() if v)
                    for v2 in Vs[pos2]:
                        f(
                            t + 1,
                            pos1,
                            v2,
                            flow + [ j ]
                        )
            Os[pos1] = False
        else:
            j = sum(Fs[k] for k, v in Os.items() if v)
            for v in Vs[pos1]:
                for k2 in (0, 1):
                    if k2 == 0:
                        if Os[pos2] or Fs[pos2] <= 0:
                            continue

                        Os[pos2] = True
                        j = sum(Fs[k] for k, v in Os.items() if v)
                        f(
                            t + 1,
                            v,
                            pos2,
                            flow + [ j ]
                        )
                        Os[pos2] = False
                    else:
                        j = sum(Fs[k] for k, v in Os.items() if v)
                        for v2 in Vs[pos2]:
                            f(
                                t + 1,
                                v,
                                v2,
                                flow + [ j ]
                            )

f(1, "AA", "AA", [ 0 ])