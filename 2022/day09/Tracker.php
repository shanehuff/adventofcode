<?php

use Illuminate\Support\Collection;

class Tracker
{
    private Collection $headVisited;

    private Collection $tailVisited;

    public function __construct()
    {
        $this->headVisited = collect();
        $this->tailVisited = collect();
    }

    public function track(array $visited): void
    {
        $this->headVisited->push($visited['head']);
        $this->tailVisited->push($visited['tail']);
    }

    public function toArray(): array
    {
        return [
            'head' => $this->headVisited,
            'tail' => $this->tailVisited,
        ];
    }
}