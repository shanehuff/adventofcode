<?php
return array_map(function ($line) {
    return explode(',', $line);
}, array_filter(explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'))));