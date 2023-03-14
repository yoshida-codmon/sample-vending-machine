<?php
declare(strict_types=1);

namespace Domain\Repository;

use Domain\Model\ProductStock\ProductStocks;

interface ProductStockRepository
{
    public function load(): ProductStocks;

    public function save(ProductStocks $stocks): void;

    public function count(): int;
}
