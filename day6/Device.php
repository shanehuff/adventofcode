<?php

use Illuminate\Support\Collection;

class Device
{
    private string $data;

    private Collection $chars;

    private int $currentPosition = -1;

    private bool $running = true;

    private int $limit;
    
    private string $currentChars = '';

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
        $this->currentChars = $this->chars->take($this->currentPosition)->join('');

        if ($this->shouldReview()) {
            $this->maybeStop();
        }
    }
    
    private function shouldReview(): bool
    {
        return $this->currentPosition >= $this->limit;
    }

    public function isRunning(): bool
    {
        return $this->currentPosition < $this->chars->count() && $this->running;
    }

    private function maybeStop(): void
    {
        $this->running = $this->isUniquePatternNotFound();
    }
    
    private function isUniquePatternNotFound(): bool
    {        
        return count(array_unique(str_split(substr($this->currentChars, -1 * $this->limit)))) < $this->limit;
    }

    public function stoppedAt(): int
    {
        return $this->currentPosition;
    }
}
