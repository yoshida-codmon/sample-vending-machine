<?php

use Domain\Model\ProductStock\ProductStock;
use Domain\Model\ProductStock\ProductStocks;
use Infrastructure\Repository\InMemoryProductStockRepository;
use PHPUnit\Framework\TestCase;

class InMemoryProductStockRepositoryTest extends TestCase
{
    public function testLoad()
    {
        $repository = new InMemoryProductStockRepository();

        // load - 初期値は空
        $stocks = $repository->load();
        $this->assertEquals(0, $stocks->count());
    }

    public function testSave()
    {
        // given
        $repository = new InMemoryProductStockRepository();
        $stocks = new ProductStocks();
        $stocks->add(ProductStock::create('cola', 100, 10));
        $stocks->add(ProductStock::create('coffee', 110, 11));

        // when
        $repository->save($stocks);
        $actual = $repository->load();

        // then
        $this->assertTrue($stocks === $actual);
    }
}