<?php

namespace Domain\Model\ProductStock;

use Core\GenericScalarIIdentity;

/**
 * @template-extends ScalarIdentity<string>
 */
class ProductId extends GenericScalarIIdentity
{
    public static function from(string $id): self
    {
        return new self($id);
    }
}
