<?php

class NumberOne
{

    private int $number;

    function __construct(int $number)
    {
        $this->number = $number;
    }

    /**
     * @return boolean
     */

    private function check(): bool
    {
        $number = $this->number;
        for ($i = 2; $i < $number; $i++) {
            if ($number % $i === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return string
     */

    public function result() : string
    {
        if ($this->check()) {
            return $this->number . ' was a prime number';
        } else {
            return $this->number . ' was not a prime number';
        }
    }
}

echo (new NumberOne(25))->result();
