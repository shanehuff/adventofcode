<?php

class Assignment
{
    private string $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function head(): int
    {
        return (int)explode('-', $this->data)[0];
    }

    public function tail(): int
    {
        return (int)explode('-', $this->data)[1];
    }

    public function toArray(): array
    {
        return [
            'head' => $this->head(),
            'tail' => $this->tail()
        ];
    }
}