<?php
return array_map(function ($line) {
    return $line;
}, array_filter(explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'))));