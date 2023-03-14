<?php

namespace Domain\Model\ProductStock;

use Core\IEntity;
use Core\GenericIEntity;
use InvalidArgumentException;

/**
 * @template-extends GenericIEntity<ProductId>
 */
class ProductStock extends GenericIEntity
{
    public function __construct(
        public readonly Product $product,
        private int             $quantity = 0
    )
    {
        parent::__construct($product->id);
    }

    public static function create(string $name, int $price, $quantity): self
    {
        if ($price < 0) {
            throw new InvalidArgumentException('The price must be greater than or equal to 0.');
        }
        if ($quantity < 0) {
            throw new InvalidArgumentException('The quantity must be greater than or equal to 0.');
        }
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
