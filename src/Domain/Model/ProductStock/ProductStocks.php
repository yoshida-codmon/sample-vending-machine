<?php
declare(strict_types=1);

namespace Domain\Model\ProductStock;

use Core\IEntity;
use Core\GenericIEntities;
use Exception;
use Generator;
use Traversable;

/**
 * @template-extends GenericIEntities<ProductStock, ProductId>
 */
class ProductStocks extends GenericIEntities
{
    /**
     * @param ProductStock $value
     * @return $this
     */
    public function add(IEntity $value): self
    {
        return parent::add($value);
    }

    /**
     * @param ProductId $key
     * @return ProductStock|null
     */
    public function get($key): ?IEntity
    {
        return parent::get($key);
    }

    /**
     * @return Traversable<ProductId, ProductStock>
     * @throws Exception
     */
    public function getIterator(): Traversable
    {
        return parent::getIterator();
    }

    /**
     * @return Generator<ProductId>
     */
    public function keys(): Generator
    {
        return parent::keys();
    }
}
