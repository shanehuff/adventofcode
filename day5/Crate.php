<?php

class Crate
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function hasStuff(): bool
    {
        return $this->data !== null;
    }

    public function toString(): string
    {
        return $this->data;
    }
}