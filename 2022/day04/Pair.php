<?php

class Pair
{
    private array $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function toArray(): array
    {
        return [
            'first_assignment' => $this->first()->toArray(),
            'second_assignment' => $this->second()->toArray(),
            'is_fully_contain_each_other' => $this->isFullyContainEachOther(),
            'is_overlap' => $this->isOverlap()
        ];
    }

    // Create function to create Assignment instance from $this->data[0]
    public function first(): Assignment
    {
        return new Assignment($this->data[0]);
    }

    // Create function to create Assignment instance from $this->data[1]
    public function second(): Assignment
    {
        return new Assignment($this->data[1]);
    }

    // Check if first contains second or second contains first‘s head or tail
    public function isFullyContainEachOther(): bool
    {
        return $this->first()->head() <= $this->second()->head() && $this->second()->tail() <= $this->first()->tail();
    }

    // Create function to check if $this->first() and $this->second() overlap
    public function isOverlap(): bool
    {
        return $this->first()->head() <= $this->second()->tail() && $this->second()->head() <= $this->first()->tail();
    }

}