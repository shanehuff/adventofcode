import re

*grid, _, path = open('22.in')
pos, dir = grid[0].index('.'), 1

path = re.findall(r'\d+|[RL]', path)
grid = {complex(x,y): c for y,l in enumerate(grid)
                        for x,c in enumerate(l) if c in '.#'}

for p in path:
    if p == 'R':  dir *=  1j
    elif p == 'L':  dir *= -1j
    else:
        for _ in range(int(p)):
            new = pos + dir
            if new in grid:
                if grid[new] == '.': pos = new
            else:  # wrap
                new = pos
                while new - dir in grid:
                    new -= dir
                if grid[new] == '.': pos = new

print(1000*(pos.imag+1) + 4*(pos.real+1) + [1,1j,-1,-1j].index(dir))