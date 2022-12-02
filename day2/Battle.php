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

            $score = [
                'round_number' => $count,
                'player1_win' => $result['is_player1_win'] ? 6 : ($result['is_draw'] ? 3 : 0),
                'player2_win' => $result['is_player2_win'] ? 6 : ($result['is_draw'] ? 3 : 0),
                'player1_hand' => $result['player1']['score'],
                'player2_hand' => $result['player2']['score']
            ];

            $score['player1_total'] = $score['player1_win'] + $score['player1_hand'];
            $score['player2_total'] = $score['player2_win'] + $score['player2_hand'];

            $this->scores->push($score);
        });
    }

    public function getPlayer2Score()
    {
        return $this->scores->sum('player2_total');
    }
}