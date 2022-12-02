<?php

use Illuminate\Support\Collection;

class Hand
{
    protected Collection $data;

    private $symbol;

    public function __construct()
    {
        $this->data = collect([
            [
                'symbol' => ['A', 'X'],
                'shape' => 'rock',
                'score' => 1,
            ],
            [
                'symbol' => ['B', 'Y'],
                'shape' => 'paper',
                'score' => 2,
            ],
            [
                'symbol' => ['C', 'Z'],
                'shape' => 'scissor',
                'score' => 3,
            ]
        ]);
    }

    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function shape()
    {
        return $this->current()['shape'];
    }

    public function score()
    {
        return $this->current()['score'];
    }

    public function strength()
    {
        return $this->score() - 1;
    }

    public function current()
    {
        return $this->data->filter(function($value, $key){
            return in_array($this->symbol, $value['symbol']);
        })->first();
    }
}