<?php

use Illuminate\Support\Collection;

class ScenicScore
{
    private Collection $scores;

    private Collection $up;

    private Collection $down;

    private Collection $left;

    private Collection $right;

    private int $width = 0;

    private int $height = 0;

    protected int $bottom = 0;

    private int $rightEdge = 0;

    public function __construct(Collection $grids)
    {
        $this->scores = collect();
        $this->up = collect();
        $this->down = collect();
        $this->left = collect();
        $this->right = collect();

        $grids->groupBy('y')->each(function (Collection $gridsY, $y) {
            $this->up->put($y, $gridsY->sortByDesc('x'));
            $this->down->put($y, $gridsY);
        });

        $grids->groupBy('x')->each(function (Collection $gridsX, $x) {
            $this->left->put($x, $gridsX->sortByDesc('y'));
            $this->right->put($x, $gridsX);
        });

        $this->width = $this->left[0]->count();
        $this->height = $this->up[0]->count();
        $this->bottom = $this->height - 1;
        $this->rightEdge = $this->width - 1;
    }

    public function score(int $x, int $y, int $height, $id = null): float|int
    {
        if ($this->isEdge($x, $y)) {
            return 0;
        }

        $up = $this->up($x, $y, $height);
        $down = $this->down($x, $y, $height);
        $left = $this->left($x, $y, $height);
        $right = $this->right($x, $y, $height);

        return abs($up * $down * $left * $right);
    }

    private function isEdge(int $x, int $y): bool
    {
        return $x === 0 || $x === $this->width - 1 || $y === 0 || $y === $this->height - 1;
    }

    private function up(int $x, int $y, int $height): int
    {
        $blockerX = $this->up[$y]
            ->where('x', '<', $x)
            ->where('height', '>=', $height)
            ->first()['x'] ?? 0;

        return $blockerX - $x;
    }

    private function down(int $x, int $y, int $height): int
    {
        $blockerX = $this->down[$y]
            ->where('x', '>', $x)
            ->where('height', '>=', $height)
            ->first()['x'] ?? $this->rightEdge;

        return $blockerX - $x;
    }

    private function left(int $x, int $y, int $height): int
    {
        $blockerY = $this->left[$x]
            ->where('y', '<', $y)
            ->where('height', '>=', $height)
            ->first()['y'] ?? 0;

        return $blockerY - $y;
    }

    private function right(int $x, int $y, int $height): int
    {
        $blockerY = $this->right[$x]
            ->where('y', '>', $y)
            ->where('height', '>=', $height)
            ->first()['y'] ?? $this->bottom;

        return $blockerY - $y;
    }
}