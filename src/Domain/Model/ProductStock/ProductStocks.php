<?php
declare(strict_types=1);

namespace Domain\Model\ProductStock;

use Domain\Model\Common\GenericEntities;
use Domain\Model\Common\IEntity;
use Exception;
use Generator;
use Traversable;

/**
 * @template-extends GenericEntities<ProductId, ProductStock>
 */
class ProductStocks extends GenericEntities
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
