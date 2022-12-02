<?php
return array_map(function ($round) {
    [$hand1, $hand2] = explode(' ', $round);
    return [
        'player1_hand' => $hand1,
        'player2_hand' => $hand2
    ];
}, array_filter(explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'))));