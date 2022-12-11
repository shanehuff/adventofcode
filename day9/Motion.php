<?php

use Illuminate\Support\Collection;

class Motion
{
    private Collection $ready;

    public function load(array $motions): void
    {
        $this->ready = collect($motions);
    }

    public function running(): bool
    {
        return $this->ready->isNotEmpty();
    }

    public function current()
    {
        return $this->ready->shift();
    }
}