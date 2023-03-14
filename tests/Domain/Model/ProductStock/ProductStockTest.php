<?php

namespace ProductStock;

use Domain\Model\ProductStock\ProductId;
use Domain\Model\ProductStock\ProductStock;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ProductStockTest extends TestCase
{
    public function testCreate()
    {
        // when
        $stock = ProductStock::create('cola', 100, 10);

        // then
        $this->assertEquals(ProductId::from('cola'), $stock->product->id);
        $this->assertEquals(100, $stock->product->price);
        $this->assertEquals(10, $stock->getQuantity());
    }

    public function testIsOutOfStock()
    {
        // when
        $stock = ProductStock::create('cola', 100, 0);

        // then
        $this->assertTrue($stock->isOutOfStock());
    }

    public function testIncrease()
    {
        // when
        $stock = ProductStock::create('cola', 100, 0);
        $stock->increase();

        // then
        $this->assertEquals(1, $stock->getQuantity());

        $stock->increase(2);

        $this->assertEquals(3, $stock->getQuantity());
    }

    public function testIncrease_InvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);

        // when
        $stock = ProductStock::create('cola', 100, 0);
        $stock->increase(-1);
    }

    public function testDecrease()
    {
        // when
        $stock = ProductStock::create('cola', 100, 3);
        $stock->decrease();

        // then
        $this->assertEquals(2, $stock->getQuantity());

        $stock->decrease(2);

        $this->assertEquals(0, $stock->getQuantity());
    }

    public function testDecrease_InvalidArgumentException1()
    {
        $this->expectException(InvalidArgumentException::class);

        // when
        $stock = ProductStock::create('cola', 100, 0);
        $stock->decrease(-1);
    }

    public function testDecrease_InvalidArgumentException2()
    {
        $this->expectException(InvalidArgumentException::class);

        // when
        $stock = ProductStock::create('cola', 100, 1);
        $stock->decrease(2);
    }
}