<?php

class NumberTwo
{

    private array $numbers;

    function __construct(array $numbers)
    {
        $this->numbers = $numbers;
    }

    /**
     * @return int
     */

    private function getHighest(): int
    {
        $numbers = $this->numbers;
        $countNumber = count($this->numbers);
        $highest = 0;

        for ($i = 0; $i < $countNumber; $i++) {
            for ($j = 0; $j < $countNumber - 1; $j++) {
                if ($numbers[$j] > $numbers[$j + 1]) {
                    $highest = $numbers[$j];
                }
            }
        }
        return $highest;
    }

    /**
     * @return string
     */

    public function result(): string
    {
        return 'the highest number is : ' . $this->getHighest();
    }
}


echo (new NumberTwo([11, 6, 31, 201, 99, 861, 1, 7, 14, 79]))->result();
