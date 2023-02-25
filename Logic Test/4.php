<?php

class NumberFour
{

    private array $numbers;

    function __construct(array $numbers)
    {
        $this->numbers = $numbers;
    }

    /**
     * @return void
     */

    private function doSorting(): void
    {
        $countNumber = count($this->numbers);
        for ($i = 0; $i < $countNumber; $i++) {
            for ($j = 0; $j < $countNumber - 1; $j++) {
                $currentNumber = $this->numbers[$j];
                $nextNumber = $this->numbers[$j + 1];
                if ($currentNumber > $nextNumber) {
                    $this->numbers[$j + 1] = $currentNumber;
                    $this->numbers[$j] = $nextNumber;
                }
            }
        }
    }

    /**
     * @return array
     */

    public function result(): array
    {
        $this->doSorting();
        return $this->numbers;
    }
}


print_r((new NumberFour([99, 2, 64, 8, 111, 33, 65, 11, 102, 50]))->result());
