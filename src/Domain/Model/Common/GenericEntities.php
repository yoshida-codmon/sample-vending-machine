<?php
declare(strict_types=1);

namespace Domain\Model\Common;

use Ds\Map;
use Exception;
use Generator;
use Traversable;

/**
 * @template TKey of IIdentity
 * @template TEntity of IEntity
 * @template-implements IEntities<TKey, TEntity>
 */
class GenericEntities implements IEntities
{
    protected Map $map;

    public function __construct()
    {
        $this->map = new Map();
    }

    /**
     * @param TEntity $value
     * @return $this
     */
    public function add(IEntity $value): self
    {
        $this->map[$value->getId()] = $value;
        return $this;
    }

    /**
     * @param TKey $key
     * @return TEntity|null
     */
    public function get(IIdentity $key): ?IEntity
    {
        if ($this->map->hasKey($key)) {
            return $this->map->get($key);
        } else {
            return null;
        }
    }

    /**
     * @return Generator<TKey>
     */
    public function keys(): Generator
    {
        return $this->map->keys()->getIterator();
    }

    /**
     * @return Traversable<TKey, TEntity>
     * @throws Exception
     */
    public function getIterator(): Traversable
    {
        return $this->map->getIterator();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->map->count();
    }
}
