<?php
$input = file_get_contents(__DIR__ . '/input.txt');
$histories = array_filter(explode('$ ', $input));

$commands = [];
foreach ($histories as $history) {
    $historyParts = explode(PHP_EOL, $history);
    $commandParts = explode(' ', $historyParts[0]);
    if (count($historyParts) === 1) {
        $commands[] = [
            'command' => $commandParts[0],
            'parameters' => array_slice($commandParts, 1)
        ];
    } else {
        $commands[] = [
            'command' => $commandParts[0],
            'parameters' => array_slice($commandParts, 1),
            'output' => array_filter(array_slice($historyParts, 1))
        ];
    }
}

return $commands;