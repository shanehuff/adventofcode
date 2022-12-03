<?php

class Rucksack
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function toArray()
    {
        return [
            'first' => $this->first(),
            'second' => $this->second(),
            'length' => $this->length(),
            'common' => $this->common(),
            'priority' => $this->priority()
        ];
    }

    private function first()
    {
        return substr($this->data, 0, $this->length() / 2);
    }

    private function second()
    {
        return substr($this->data, ($this->length() / 2), $this->length());
    }

    private function length()
    {
        return strlen($this->data);
    }

    private function common()
    {
        return collect(array_intersect(
            str_split($this->first()),
            str_split($this->second())
        ))->first();
    }

    private function priority()
    {
        return array_search($this->common(), str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')) + 1;
    }
}