<?php

class Group
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
            'third' => $this->third(),
            'common' => $this->common(),
            'priority' => $this->priority()
        ];
    }

    private function first()
    {
        return $this->data[0];
    }

    private function second()
    {
        return $this->data[1];
    }

    private function third()
    {
        return $this->data[2];
    }

    private function common()
    {
        return collect(array_intersect(
            str_split($this->first()),
            str_split($this->second()),
            str_split($this->third())
        ))->first();
    }

    private function priority()
    {
        return array_search($this->common(), str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')) + 1;
    }
}