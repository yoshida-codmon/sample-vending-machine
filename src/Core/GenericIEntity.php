<?php
declare(strict_types=1);

namespace Core;

use Ds\Hashable;

/**
 * @template T of IIdentity
 * @template-implements IEntity<T>
 */
class GenericIEntity implements IEntity, Hashable
{
    /**
     * @param IIdentity $id
     */
    public function __construct(
        protected readonly IIdentity $id
    )
    {
    }

    /**
     * @return T
     */
    public function getId(): IIdentity
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function equals(mixed $obj) : bool
    {
        return $obj !== null && $this->getId()->equals($obj->getId());
    }

    public function hash()
    {
        return $this->id->hash();
    }
}
