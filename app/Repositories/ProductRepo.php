<?php

namespace App\Repositories;

class ProductRepo
{
    public function find(int $id): array
    {
        // simulasi data dari DB
        return [
            'id' => $id,
            'price' => 100_000
        ];
    }
}
