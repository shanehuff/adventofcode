<?php
require_once __DIR__ . '/vendor/autoload.php';

$scoreShape = [
    ['opponent' => 'A', 'you' => 'X', 'shape' => 'rock', 'score' => 1],
    ['opponent' => 'B', 'you' => 'Y', 'shape' => 'paper', 'score' => 2],
    ['opponent' => 'C', 'you' => 'Z', 'shape' => 'scissor', 'score' => 3]
];

$scorePlus = [
    'lost' => 0,
    'draw' => 3,
    'win' => 6
];

$input = file_get_contents(__DIR__ . '/input.txt');
$rounds = explode(PHP_EOL, $input);

function calculate_round_score(string $opponent, string $you)
{
    global $scoreShape, $scorePlus;
    $scores = collect($scoreShape);
    
    $yours = $scores->where('you', '=', $you)->first();
    $opponents = $scores->where('opponent', '=', $opponent)->first();
    
    $youWin = 'rock' === $yours['shape'] && 'scissor' === $opponents['shape']
        || 'paper' === $yours['shape'] && 'rock' === $opponents['shape']
        || 'scissor' === $yours['shape'] && 'paper' === $opponents['shape'];

    if($youWin) {
        return $yours['score'] + $scorePlus['win'];
    }

    if($yours['score'] === $opponents['score']) {
        return $yours['score'] + $scorePlus['draw'];
    }

    return $yours['score'] + $scorePlus['lost'];
}

function calculate_round_score_p2(string $opponent, string $result)
{
    global $scoreShape, $scorePlus;
    $scores = collect($scoreShape);
    
    $opponents = $scores->where('opponent', '=', $opponent)->first();
    $yours = ['A' => 'X', 'B' => 'Y', 'C' => 'Z'];

    if('Y' === $result) {
        return calculate_round_score($opponent, $yours[$opponent]);
    }
    
    if('Z' === $result) {
        return calculate_round_score($opponent, choose_your_shape($opponent, 'win'));
    }

    if('X' === $result) {
        return calculate_round_score($opponent, choose_your_shape($opponent, 'lose'));
    }
}

function choose_your_shape($opponent, $result)
{
    global $scoreShape;
    $scores = collect($scoreShape);

    $opponents = $scores->where('opponent', '=', $opponent)->first();

    if('rock' === $opponents['shape']) {
       return 'win' === $result ? $scores->where('shape', '=', 'paper')->first()['you']
           : $scores->where('shape', '=', 'scissor')->first()['you'];
    }

    if('paper' === $opponents['shape']) {
       return 'win' === $result ? $scores->where('shape', '=', 'scissor')->first()['you'] 
           : $scores->where('shape', '=', 'rock')->first()['you'];
    }

    if('scissor' === $opponents['shape']) {
       return 'win' === $result ? $scores->where('shape', '=', 'rock')->first()['you'] 
           : $scores->where('shape', '=', 'paper')->first()['you'];
    }

    return false;
}

$total = 0;

foreach($rounds as $round) {
    $players = explode(' ', $round);

    if(isset($players[0]) && isset($players[1])) {
        $total+= calculate_round_score_p2($players[0], $players[1]);
    }
}

dd($total);
