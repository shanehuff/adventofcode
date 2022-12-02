<?php

use Illuminate\Support\Collection;

class Battle
{
    private Collection $rounds;
    private Collection $results;
    private Collection $scores;

    public function __construct(array $rounds)
    {
        $this->rounds = collect($rounds);
        $this->scores = collect();
        $this->results = collect();
    }

    public function scores(): array
    {
        return $this->scores->toArray();
    }

    public function fight(): void
    {
        $this->rounds->each(function ($round) {
            $this->results->push((new Round($round))->toArray());
        });

        $this->calculateScores();
    }

    private function calculateScores(): void
    {
        $this->results->each(function ($result, $count) {
            ++$count;
            $this->scores->push([
                'round_number' => $count,
                'player1' => $result['is_player1_win'] ? 6 : ($result['is_draw'] ? 3 : 0) + $result['player1']['hand_score'],
                'player2' => $result['is_player2_win'] ? 6 : ($result['is_draw'] ? 3 : 0) + $result['player2']['hand_score']
            ]);
        });
    }

    public function getPlayer2Score()
    {
        return $this->scores->sum('player2');
    }
}