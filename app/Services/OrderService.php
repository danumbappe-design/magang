<?php 

namespace App\Services;

use App\Repositories\ProductRepo;

class OrderService
{
    private ProductRepo $repository;

    public function __construct(ProductRepo $repository)
    {
        $this->repository = $repository;
    }

    public function finalPrice(int $productId): int
    {
        $product = $this->repository->find($productId);

        return (int) ($product['price'] * 0.9);
    }
}
