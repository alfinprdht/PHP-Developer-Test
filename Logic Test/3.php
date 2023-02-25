<?php

class NumberThree
{

    private int $length;

    function __construct(int $length)
    {
        $this->length = $length;
    }

    /**
     * @return void
     */

    public function result(): void
    {
        for ($i = 1; $i <= $this->length; $i++) {
            for ($j = 1; $j < $i; $j++) {
                echo $j . ' ';
            }
            echo $i . ' ' . PHP_EOL;
        }
    }
}


echo (new NumberThree(15))->result();
