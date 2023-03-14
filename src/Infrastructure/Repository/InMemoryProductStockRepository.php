<?php

namespace Infrastructure\Repository;

use Domain\Model\ProductStock\ProductStocks;
use Domain\Repository\ProductStockRepository;

class InMemoryProductStockRepository implements ProductStockRepository
{
    public function __construct(
        private ProductStocks $values = new ProductStocks()
    )
    {
    }

    public function load(): ProductStocks
    {
        return $this->values;
    }

    public function save(ProductStocks $stocks): void
    {
        $this->values = $stocks;
    }
}
