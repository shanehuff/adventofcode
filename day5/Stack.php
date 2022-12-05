<?php

use Illuminate\Support\Collection;

class Stack
{

    private Collection $crates;
    private Collection $moves;
    private int $cratesCount = 0;
    private int $rows;
    private int $columns;
    private Collection $grids;

    public function __construct()
    {
        $this->grids = collect();
    }

    public function setCrates(Collection $crates): void
    {
        $this->crates = $crates;
        $this->setupCrates();
    }

    public function setMoves(Collection $moves): void
    {
        $this->moves = $moves;
    }

    public function toArray(): array
    {
        return [
            'crate_total' => $this->cratesCount,
            'rows' => $this->rows,
            'columns' => $this->columns,
            'grids' => $this->grids
        ];
    }

    private function setupCrates(): void
    {
        $columns = [];
        $rows = 0;

        // Analyze the crates first to collect the rows and columns
        $this->crates->each(function ($crates) use (&$columns, &$rows) {
            $columns[] = count($crates);
            $rows++;
        });

        $this->columns = max($columns);
        $this->rows = $rows;

        for ($col = 0; $col <= $this->columns; $col++) {
            $this->crates->each(function ($crates, $index) use ($col) {

                if (isset($crates[$col])) {
                    $this->grids->push([
                        'id' => uniqid(),
                        'index' => $this->rows - $index,
                        'col' => $col,
                        'content' => $crates[$col] ?? null,
                    ]);
                    $this->cratesCount++;
                }
            });
        }
    }

    public function moveMultipleAtATime(): void
    {
        $this->moves->each(function ($move) {
            if ($cells = $this->getTopCellsByCol($move->from(), $move->quantity())) {
                $this->moveCellsToCol($cells, $move->to());
            }
        });
    }

    public function moveOneAtATime(): void
    {
        $this->moves->each(function ($move) {
            while ($move->quantity() > 0) {
                if ($cell = $this->getTopCellByCol($move->from())) {
                    $this->moveCellToCol($cell, $move->to());
                }
                $move->decrementQuantity();
            }
        });
    }

    private function getTopCellByCol(int $from)
    {
        return $this->grids->where('col', $from)->sortByDesc('index')->first();
    }

    private function moveCellToCol($fromCell, int $toCol): void
    {
        // Get count of cell where col is $toCol
        $newIndex = $this->getTopCellByCol($toCol) ? $this->getTopCellByCol($toCol)['index'] + 1 : 1;

        $this->grids->transform(function ($cell) use ($newIndex, $fromCell, $toCol) {
            if ($cell['id'] === $fromCell['id']) {
                $cell['col'] = $toCol;
                $cell['index'] = $newIndex;
            }

            return $cell;
        });
    }

    public function toLetters(): string
    {
        $letters = [];
        for ($i = 1; $i <= $this->columns; $i++) {
            $cell = $this->getTopCellByCol($i);
            if ($cell) {
                $letters[] = $cell['content']->toString();
            };
        }

        return implode('', $letters);
    }

    private function getTopCellsByCol($from, $quantity): Collection
    {
        return $this->grids->where('col', $from)->sortByDesc('index')->take($quantity);
    }

    private function moveCellsToCol(Collection $cells, $to): void
    {
        $cells->sortBy('index')->each(function ($cell) use ($to) {
            $this->moveCellToCol($cell, $to);
        });
    }
}