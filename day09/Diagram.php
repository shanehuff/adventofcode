<?php

use Illuminate\Support\Collection;

class Diagram
{
    private Tracker $tracker;
    private Motion $motion;
    private Rope $rope;
    private int $width = 6;
    private int $height = 5;
    private Collection $grids;
    private array $start = [
        'x' => 0,
        'y' => 4,
    ];
    private int $j = 0;

    public function __construct()
    {
        $this->tracker = new Tracker;
        $this->motion = new Motion;

        $this->createGrids();
        $this->createRope();
    }

    private function createGrids(): void
    {
        $this->grids = collect();
        $id = 0;
        for ($x = 0; $x < $this->width; $x++) {
            for ($y = 0; $y < $this->height; $y++) {
                $this->grids->put(++$id, [
                    'id' => $id,
                    'x' => $x,
                    'y' => $y
                ]);
            }
        }
    }

    private function createRope(): void
    {
        $this->rope = new Rope($this->cell(...$this->start));
    }

    private function cell(int $x, int $y): ?array
    {
        $id = $x * $this->height + $y + 1;
        $cell = $this->grids->get($id);
        unset($cell['id']);

        return $cell;
    }

    public function loadMotions(mixed $motions): void
    {
        $this->motion->load($motions);
    }

    public function run(): void
    {
        while ($this->motion->running()) {
            $this->moveRope();
        }
    }

    private function moveRope(): void
    {
        extract($this->motion->current());

        switch ($direction) {
            case 'L':
                $this->moveRopeLeft($length);
                break;
            case 'R':
                $this->moveRopeRight($length);
                break;
            case 'U':
                $this->moveRopeUp($length);
                break;
            case 'D':
                $this->moveRopeDown($length);
                break;
        }
    }

    private function moveRopeLeft(int $length): void
    {
        for ($i = 0; $i < $length; $i++) {
            $this->rope->moveLeft();
            $this->tracker->track($this->rope->current());
        }
    }

    private function moveRopeRight(int $length): void
    {
        for ($i = 0; $i < $length; $i++) {
            $this->rope->moveRight();
            $this->tracker->track($this->rope->current());
        }
    }

    private function moveRopeUp(int $length): void
    {
        for ($i = 0; $i < $length; $i++) {
            $this->rope->moveUp();
            $this->tracker->track($this->rope->current());
        }
    }

    private function moveRopeDown($length): void
    {
        for ($i = 0; $i < $length; $i++) {
            $this->rope->moveDown();
            $this->tracker->track($this->rope->current());
        }

    }

    public function toArray(): array
    {
        return [
            'tracker' => $this->tracker->toArray(),
            'rope' => $this->rope->toArray(),
        ];
    }

    private function drawGrids(): void
    {
        $this->grids->groupBy('y')->each(function ($group) {
            $group->each(function ($grid) {
                echo sprintf('|%2d %d %d|', $grid['id'], $grid['x'], $grid['y']) . ' ';
            });
            echo PHP_EOL;
        });
    }


}