<?php

class Player
{
    public Hand $hand;

    public function __construct()
    {
        $this->hand = new Hand;
    }

    public function useHand(string $hand): static
    {
        $this->hand->setSymbol($hand);

        return $this;
    }

    public function toArray(): array
    {
        return [
            'shape' => $this->hand->shape(),
            'score' => $this->hand->score(),
        ];
    }
}