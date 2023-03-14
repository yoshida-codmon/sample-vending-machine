<?php
declare(strict_types=1);

namespace Core;

use Generator;
use IteratorAggregate;

/**
 * @template TKey of IIdentity
 * @template TEntity of IEntity
 * @template-extends IteratorAggregate<TKey, TEntity>
 */
interface IEntities extends IteratorAggregate
{
    /**
     * @param TEntity $value
     * @return IEntities<TKey, TEntity>
     */
    public function add(IEntity $value): self;

    /**
     * @param TKey $key
     * @return TEntity|null
     */
    public function get(IIdentity $key): IEntity|null;

    /**
     * @return Generator<TKey>
     */
    public function keys(): Generator;
}
