<?php

class Round
{
    private Player $player1;
    private Player $player2;

    public function __construct(array $round)
    {
        $this->player1 = (new Player)->useHand($round['player1_hand']);
        $this->player2 = (new Player)->useHand($round['player2_hand']);
    }

    public function toArray(): array
    {
        return [
            'player1' => $this->player1->toArray(),
            'player2' => $this->player2->toArray(),
            'is_player2_win' => $this->isPlayer2Win(),
            'is_draw' => $this->isDraw(),
            'is_player1_win' => !($this->isPlayer2Win() || $this->isDraw())
        ];
    }

    private function isPlayer2Win(): bool
    {
        return match ($this->player2->hand->shape()) {
            'rock', 'paper' => $this->player2->hand->score() - $this->player1->hand->score() > 0,
            'scissor' => 1 === $this->player1->hand->score(),
            default => false,
        };

    }

    private function isDraw(): bool
    {
        return $this->player2->hand->score() === $this->player1->hand->score();
    }
}