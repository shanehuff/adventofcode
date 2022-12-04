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

    private function first(): Assignment
    {
        return new Assignment($this->data[0]);
    }

    private function second(): Assignment
    {
        return new Assignment($this->data[1]);
    }

    private function isFullyContainEachOther(): bool
    {
        return $this->firstContainsSecond() || $this->secondContainsFirst();
    }

    private function firstContainsSecond(): bool
    {
        return $this->first()->head() <= $this->second()->head()
            && $this->first()->tail() >= $this->second()->tail();
    }

    private function secondContainsFirst(): bool
    {
        return $this->second()->head() <= $this->first()->head()
            && $this->second()->tail() >= $this->first()->tail();
    }

    private function isOverlap(): bool
    {
        return $this->firstOverlapSecond() || $this->secondOverlapFirst();
    }

    private function firstOverlapSecond(): bool
    {
        return $this->secondHeadInsideFirst() || $this->secondTailInsideFirst();
    }

    private function secondOverlapFirst(): bool
    {
        return $this->firstHeadInsideSecond() || $this->firstTailInsideSecond();
    }

    private function secondHeadInsideFirst(): bool
    {
        return $this->first()->head() <= $this->second()->head()
            && $this->second()->head() <= $this->first()->tail();
    }

    private function secondTailInsideFirst(): bool
    {
        return $this->first()->head() <= $this->second()->tail()
            && $this->second()->tail() <= $this->first()->tail();
    }

    private function firstHeadInsideSecond(): bool
    {
        return $this->second()->head() <= $this->first()->head()
            && $this->first()->head() <= $this->second()->tail();
    }

    private function firstTailInsideSecond(): bool
    {
        return $this->second()->head() <= $this->first()->tail()
            && $this->first()->tail() <= $this->second()->tail();
    }


}