<?php
declare(strict_types=1);

namespace Domain\Model\Common;

use Ds\Hashable;

/**
 * @template T of IIdentity
 * @template-implements IEntity<T>
 */
class GenericEntity implements IEntity, Hashable
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
     * @inheritDoc
     */
    public function equals(mixed $obj): bool
    {
        return $obj !== null && $this->getId()->equals($obj->getId());
    }

    /**
     * @return T
     */
    public function getId(): IIdentity
    {
        return $this->id;
    }

    public function hash()
    {
        return $this->id->hash();
    }
}
