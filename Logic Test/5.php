<?php

class NumberFive
{

    private int $length;
    const VERTICAL_LENGTH = 4;

    function __construct(int $length)
    {
        $this->length = $length;
    }

    /**
     * @return void
     */

    public function result(): void
    {
        $length = $this->length;
        for ($i = 1; $i <= $length; $i++) {
            if ($i > SELF::VERTICAL_LENGTH) {
                break;
            }
            echo $i . ' ';
            $number = $i;
            for ($j = 1; $j <= $length; $j++) {
                $number += SELF::VERTICAL_LENGTH;
                if ($number > $length) {
                    break;
                }
                echo $number . ' ';
            }
            echo PHP_EOL;
        }
    }
}


echo (new NumberFive(15))->result();
