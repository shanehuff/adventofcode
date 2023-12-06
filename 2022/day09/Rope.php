<?php

class Rope
{
    private array $head;
    private array $tail;
    private array $start;

    public const DIRECTION_UP = 'up';
    public const DIRECTION_DOWN = 'down';
    public const DIRECTION_LEFT = 'left';
    public const DIRECTION_RIGHT = 'right';

    public function __construct(array $grid)
    {
        $this->head = $grid;
        $this->tail = $grid;
        $this->start = $grid;
    }

    public function toArray(): array
    {
        return [
            'head' => $this->head,
            'tail' => $this->tail,
            'start' => $this->start,
        ];
    }

    public function current(): array
    {
        return [
            'head' => $this->head,
            'tail' => $this->tail
        ];
    }

    public function moveRight(): void
    {
        if ($this->tailFollowRight()) {
            $this->moveTail(self::DIRECTION_RIGHT);
        }

        if ($this->shouldFollowRight()) {
            $this->tail = $this->head;
        }

        $this->moveHead(self::DIRECTION_RIGHT);
    }

    private function overlap(): bool
    {
        return $this->head['x'] === $this->tail['x'] && $this->head['y'] === $this->tail['y'];
    }

    private function notOverlap(): bool
    {
        return $this->overlap() === false;
    }

    private function sameRow(): bool
    {
        return $this->head['y'] === $this->tail['y'];
    }

    private function moveTail(string $direction): void
    {
        switch ($direction) {
            case self::DIRECTION_RIGHT:
                $this->tail['x'] += 1;
                break;
            case self::DIRECTION_UP:
                $this->tail['y'] -= 1;
                break;
            case self::DIRECTION_LEFT:
                $this->tail['x'] -= 1;
                break;
            case self::DIRECTION_DOWN:
                $this->tail['y'] += 1;
                break;
        }
    }

    private function moveHead(string $direction): void
    {
        switch ($direction) {
            case self::DIRECTION_RIGHT:
                $this->head['x'] += 1;
                break;
            case self::DIRECTION_UP:
                $this->head['y'] -= 1;
                break;
            case self::DIRECTION_LEFT:
                $this->head['x'] -= 1;
                break;
            case self::DIRECTION_DOWN:
                $this->head['y'] += 1;
                break;
        }
    }

    private function tailFollowRight(): bool
    {
        return $this->notOverlap()
            && $this->sameRow()
            && $this->tail['x'] === $this->head['x'] - 1;
    }

    public function moveUp(): void
    {
        if ($this->tailFollowUp()) {
            $this->moveTail(self::DIRECTION_UP);
        }

        if ($this->shouldFollowUp()) {
            $this->tail = $this->head;
        }

        $this->moveHead(self::DIRECTION_UP);
    }

    private function tailFollowUp(): bool
    {
        return $this->notOverlap() && $this->sameCol() && $this->tail['y'] === $this->head['y'] + 1;
    }

    private function sameCol(): bool
    {
        return $this->head['x'] === $this->tail['x'];
    }

    private function shouldFollowUp(): bool
    {
        return $this->notOverlap()
            && $this->head['y'] < $this->tail['y']
            && abs($this->head['x'] - $this->tail['x']) === abs($this->head['y'] - $this->tail['y']);
    }

    public function moveLeft(): void
    {
        if ($this->tailFollowLeft()) {
            $this->moveTail(self::DIRECTION_LEFT);
        }

        if ($this->shouldFollowLeft()) {
            $this->tail = $this->head;
        }

        $this->moveHead(self::DIRECTION_LEFT);
    }

    public function moveDown(): void
    {
        if ($this->tailFollowDown()) {
            $this->moveTail(self::DIRECTION_DOWN);
        }

        if ($this->shouldFollowDown()) {
            $this->tail = $this->head;
        }

        $this->moveHead(self::DIRECTION_DOWN);
    }

    private function debug(): void
    {
        echo "head: {$this->head['x']}, {$this->head['y']}, tail: {$this->tail['x']}, {$this->tail['y']}" . PHP_EOL;
    }

    private function tailFollowLeft(): bool
    {
        return $this->notOverlap() && $this->sameRow() && $this->tail['x'] === $this->head['x'] + 1;
    }

    private function shouldFollowLeft(): bool
    {
        return $this->notOverlap()
            && $this->head['x'] < $this->tail['x']
            && abs($this->head['x'] - $this->tail['x']) === abs($this->head['y'] - $this->tail['y']);
    }

    private function tailFollowDown(): bool
    {
        return $this->notOverlap() && $this->sameCol() && $this->tail['y'] === $this->head['y'] - 1;
    }

    private function shouldFollowDown(): bool
    {
        return $this->notOverlap()
            && $this->head['y'] > $this->tail['y']
            && abs($this->head['x'] - $this->tail['x']) === abs($this->head['y'] - $this->tail['y']);
    }

    private function shouldFollowRight(): bool
    {
        return $this->notOverlap()
            && $this->head['x'] > $this->tail['x']
            && abs($this->head['x'] - $this->tail['x']) === abs($this->head['y'] - $this->tail['y']);
    }

    private function notSameCol(): bool
    {
        return $this->sameCol() === false;
    }
}