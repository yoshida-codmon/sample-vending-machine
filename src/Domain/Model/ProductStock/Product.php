<?php
declare(strict_types=1);

namespace Domain\Model\ProductStock;

class Product
{
    public function __construct(
        public readonly ProductId $id,
        public readonly int       $price
    )
    {
    }
}
