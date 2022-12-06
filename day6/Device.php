<?php

use Illuminate\Support\Collection;

class Device
{
    private string $data;

    private Collection $chars;

    private int $currentPosition = -1;

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
            $this->maybeStop($currentChars);
        }
    }

    public function isRunning(): bool
    {
        return $this->currentPosition < $this->chars->count() && $this->running;
    }

    private function maybeStop($chars): void
    {
        $latestChars = substr($chars, -1 * $this->limit);

        $this->running = count(array_unique(str_split($latestChars))) < $this->limit;
    }

    public function stoppedAt(): int
    {
        return $this->currentPosition;
    }
}
