<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Cal;
use App\Services\OrderService;
use App\Repositories\ProductRepo;
use Mockery;

class CalculatorTest extends TestCase
{
    // public function test_add_two_numbers()
    // {
    //     // Arrange
    //     $calculator = new Cal();

    //     // Act
    //     $result = $calculator->add(2, 3);

    //     // Assert
    //     $this->assertEquals(5, $result);
    // }

    // public function test_price_below_100k_gets_no_discount()
    // {
    //     $service = new Cal();

    //     $result = $service->calculate(50_000);

    //     $this->assertEquals(50_000, $result);
    // }

    // public function test_price_equal_100k_gets_discount()
    // {
    //     $service = new Cal();

    //     $result = $service->calculate(100_000);

    //     $this->assertEquals(90_000, $result);
    // }

    // public function test_price_above_100k_gets_discount()
    // {
    //     $service = new Cal();

    //     $result = $service->calculate(200_000);

    //     $this->assertEquals(180_000, $result);
    // }
    // public function test_price_zero()
    // {
    //     $service = new Cal();

    //     $result = $service->calculate(0);
    //     $this->assertEquals(0, $result);
    // }

    public function test_final_price_is_discounted()
    {
        // Arrange
        $repo = Mockery::mock(ProductRepo::class);

        $repo->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn(['price' => 100_000]);

        $service = new OrderService($repo);

        // Act
        $result = $service->finalPrice(1);

        // Assert
        $this->assertEquals(90_000, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
