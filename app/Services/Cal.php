<?php

namespace App\Services;

class Cal
{
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }

        public function calculate(int $price): int
    {
        if ($price >= 100_000) {
            return (int) ($price * 0.9);
        } else if ($price <= 0) {
            return 0;
        }

        return $price;
    }
}
