<?php

class Move
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function toArray()
    {
        return [
            'quantity' => $this->quantity(),
            'from' => $this->from(),
            'to' => $this->to(),
        ];
    }

    public function quantity()
    {
        return $this->data[0];
    }

    public function from()
    {
        return $this->data[1];
    }

    public function to()
    {
        return $this->data[2];
    }

    public function decrementQuantity(): void
    {
        $this->data[0]--;
    }
}