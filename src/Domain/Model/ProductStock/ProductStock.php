<?php
declare(strict_types=1);

namespace Domain\Model\ProductStock;

use Domain\Model\Common\GenericEntity;
use InvalidArgumentException;

/**
 * @template-extends GenericEntity<ProductId>
 */
class ProductStock extends GenericEntity
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

    public function increase(int $quantity = 1)
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException("The increase quantity must be greater than 0.");
        }
        $this->quantity += $quantity;
    }

    public function decrease(int $quantity = 1)
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException("The decrease quantity must be greater than 0.");
        }
        if ($this->quantity < $quantity) {
            throw new InvalidArgumentException("The decrease quantity is greater than the amount of stock.");
        }
        $this->quantity -= $quantity;
    }
}
