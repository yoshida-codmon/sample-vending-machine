<?php

namespace Domain\Model\ProductStock;

use Core\IEntity;
use Core\GenericIEntity;

/**
 * @template-extends GenericIEntity<ProductId>
 */
class ProductStock extends GenericIEntity
{
    public static function create(string $name, int $price, $quantity): self
    {
        return new self(
            new Product(ProductId::from($name), $price),
            $quantity
        );
    }

    public function __construct(
        public readonly Product $product,
        private int $quantity = 0
    )
    {
        parent::__construct($product->id);
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
