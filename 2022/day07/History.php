<?php

class History
{
    private array $data;
    public function __construct($history)
    {
        $this->data = $history;
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data
        ];
    }

    public function command(): string
    {
        return $this->data['command'];
    }

    public function parameters()
    {
        return $this->data['parameters'];
    }

    public function output()
    {
        return $this->data['output'];
    }
}