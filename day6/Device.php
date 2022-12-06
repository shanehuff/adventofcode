<?php

use Illuminate\Support\Collection;

class Device
{
    private string $data;

    private Collection $chars;

    private int $currentPosition = -1;

    private string $currentChars;

    private bool $running = true;

    private int $limit;

    public function __construct($data, $limit = 4)
    {
        $this->data = $data;
        $this->chars = collect(str_split($data));
        $this->limit = $limit;
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'chars' => $this->chars,
        ];
    }

    public function stream(): void
    {
        $this->currentPosition++;
        $currentChars = $this->chars->take($this->currentPosition)->join('');

        if ($this->currentPosition >= $this->limit) {
            $this->running = $this->maybeStop($currentChars);
        }

        $this->currentChars = $currentChars;
    }

    public function isRunning(): bool
    {
        return $this->currentPosition < $this->chars->count() && $this->running;
    }

    private function maybeStop($chars): bool
    {
        // Get latest 4 chars from $chars
        $latestChars = substr($chars, -1 * $this->limit);

        // Check if $latestChars contains 4 different chars
        return count(array_unique(str_split($latestChars))) < $this->limit;
    }

    public function stoppedAt(): int
    {
        return $this->currentPosition;
    }
}