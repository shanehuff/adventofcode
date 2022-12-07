<?php

class Crate
{
    private ?string $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data
        ];
    }

    public function hasStuff(): bool
    {
        return $this->data !== null;
    }

    public function toString(): string
    {
        return $this->data;
    }

    public function toGraphic(): string
    {
        return sprintf('[ %s ]', $this->data);
    }
}