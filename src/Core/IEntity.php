<?php
declare(strict_types=1);

namespace Core;

/**
 * @template T of IIdentity
 */
interface IEntity
{
    /**
     * @return T
     */
    public function getId(): IIdentity;

    /**
     * @param T|null $other
     * @return bool
     */
    public function equals(mixed $other) : bool;
}
