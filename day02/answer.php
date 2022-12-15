<?php
require_once __DIR__ . '/vendor/autoload.php';

$rounds = include __DIR__ . '/stubs/rounds.php';
$battle = new Battle($rounds);

$battle->fight();

dd($battle->getPlayer2Score());
