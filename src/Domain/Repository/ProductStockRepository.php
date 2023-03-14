<?php

namespace Domain\Repository;

use Domain\Model\ProductStock\ProductStocks;

interface ProductStockRepository
{
    public function load(): ProductStocks;

    public function save(ProductStocks $stocks): void;
}
