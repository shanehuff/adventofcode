<?php
$input = file_get_contents(__DIR__ . '/input.txt');
$elves = explode(PHP_EOL . PHP_EOL, $input);
$calories = [];

foreach($elves as $elf) {
    $calories[] = array_reduce(explode(PHP_EOL, $elf), function($carry, $calo) {
        return $carry + (int)$calo;
    });
}

$maxCalories = max($calories);
$elfNumber = array_search($maxCalories, $calories) + 1;

$answer = sprintf('The elf number %d carry the most calories: (%d)', $elfNumber, $maxCalories);

echo $answer;

// Part 2

rsort($calories);

$answerPart2 = sprintf('Total Calories carried by top 3 elves: %d', $calories[0] + $calories[1] + $calories[2]);

echo $answerPart2;
