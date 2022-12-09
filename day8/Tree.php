<?php

class Tree
{
    public function __construct(
        public int $height,
        public int $id
    ) {}

    public function draw(): string
    {
        return sprintf(' 🎄 %s, %d ', $this->name(), $this->height);
    }

    public function __toString(): string
    {
        return $this->draw();
    }

    private function name(): string
    {
        return str_pad($this->id, 2, '0', STR_PAD_LEFT);
    }
}