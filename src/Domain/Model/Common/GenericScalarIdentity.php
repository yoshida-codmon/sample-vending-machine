<?php
declare(strict_types=1);

namespace Domain\Model\Common;

/**
 * @template T of string|int
 */
class GenericScalarIdentity implements IIdentity
{

    /**
     * @param T $id
     */
    public function __construct(
        private readonly mixed $id
    )
    {
    }

    public function equals($obj): bool
    {
        if (get_class($this) !== get_class($obj)) {
            return false;
        }
        return $this->id === $obj->getValue();
    }

    /**
     * @return T
     */
    public function getValue(): mixed
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string)$this->id;
    }

    public function hash(): mixed
    {
        return $this->id;
    }
}
