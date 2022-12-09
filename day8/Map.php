<?php

use Illuminate\Support\Collection;

class Map
{
    protected Collection $grids;

    private int $width = 0;

    private int $height = 0;

    private int $depth = 0;

    private int $total = 0;

    private Collection $left;

    private Collection $top;

    private Collection $bottom;
    private Collection $right;

    public function __construct()
    {
        $this->grids = collect();
        $this->left = collect();
        $this->top = collect();
        $this->bottom = collect();
        $this->right = collect();
    }

    public function create(array $grids): void
    {
        $id = 0;
        collect($grids)->each(function ($trees, $x) use (&$id) {
            collect($trees)->each(function ($tree, $y) use ($x, &$id) {
                $this->grids->push([
                    'id' => ++$id,
                    'x' => $x,
                    'y' => $y,
                    'tree' => new Tree($tree, $id),
                ]);
            });
        });

        $this->width = $this->grids->max('x') + 1;
        $this->height = $this->grids->max('y') + 1;
        $this->depth = $this->grids->max('tree.height');
        $this->total = $id;
    }

    public function toArray(): array
    {
        return [
            'grids' => $this->grids->toArray(),
            'width' => $this->width,
            'height' => $this->height,
            'depth' => $this->depth,
            'total' => $this->total
        ];
    }

    public function renderAbove(): string
    {
        $map = '';
        $this->grids->groupBy('x')->each(function ($rows, $x) use (&$map) {
            $map .= $this->drawRow($rows, $x);
        });

        return $map;
    }

    public function countVisible(): int
    {
        return collect([
            $this->top(),
            $this->bottom(),
            $this->left(),
            $this->right()
        ])->map(function ($screen) {
            return $this->visible($screen)->map(function ($grid) {
                return $grid->pluck('tree.id');
            });
        })->flatten()->unique()->count();
    }

    public function top(): Collection
    {
        if ($this->top->isEmpty()) {
            $this->grids->groupBy('y')->each(function ($rows) {
                $rows->each(function ($grid) {
                    $this->makeScreenTree($grid['tree'], $grid['y'], $this->top);
                });
            });
        }

        return $this->top;
    }

    public function renderTop(): string
    {
        return $this->renderScreen($this->top());
    }

    public function bottom(): Collection
    {
        if ($this->bottom->isEmpty()) {
            $this->grids->groupBy('y')->each(function (Collection $rows) {
                $rows->reverse()->each(function ($grid) {
                    $this->makeScreenTree($grid['tree'], $grid['y'], $this->bottom);
                });
            });
        }

        return $this->bottom;
    }

    public function renderBottom(): string
    {
        return $this->renderScreen($this->bottom());
    }

    public function right(): Collection
    {
        if ($this->right->isEmpty()) {
            $this->grids->groupBy('x')->each(function (Collection $rows) {
                $rows->reverse()->each(function ($grid) {
                    $this->makeScreenTree($grid['tree'], $grid['x'], $this->right);
                });
            });
        }

        return $this->right;
    }

    public function renderRight(): string
    {
        return $this->renderScreen($this->right());
    }

    public function left(): Collection
    {
        if ($this->left->isEmpty()) {
            $this->grids->groupBy('x')->each(function ($rows) {
                $rows->each(function ($grid) {
                    $this->makeScreenTree($grid['tree'], $grid['x'], $this->left);
                });
            });
        }

        return $this->left;
    }

    public function renderLeft(): string
    {
        return $this->renderScreen($this->left());
    }

    private function visible(Collection $screen)
    {
        return $screen
            ->sortByDesc('z')
            ->groupBy('z')
            ->map(function ($lines) {
                return $lines->groupBy('x')->map(function ($line) {
                    return $line->first();
                });
            });
    }

    private function renderScreen(Collection $screen): string
    {
        $output = '';
        $this->visible($screen)
            ->each(function ($lines) use (&$output) {
                for ($i = 0; $i < $this->width; $i++) {
                    if ($line = $lines->where('x', $i)->first()) {
                        $output .= $line['tree']->draw();
                    } else {
                        $output .= '          ';
                    }
                }
                $output .= PHP_EOL;
            });

        return $output;
    }

    private function drawRow($rows): string
    {
        $row = '';
        $rows->each(function ($tree) use (&$row) {
            $row .= $tree['tree']->draw();
        });

        return $row . PHP_EOL;
    }

    private function makeScreenTree(Tree $tree, int $index, Collection &$screen): void
    {
        for ($z = $tree->height; $z >= 0; $z--) {
            $screen->push([
                'x' => $index,
                'z' => $z,
                'tree' => $tree,
            ]);
        }
    }
}