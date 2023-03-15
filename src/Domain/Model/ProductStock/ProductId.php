<?php
declare(strict_types=1);

namespace Domain\Model\ProductStock;


use Domain\Model\Common\GenericScalarIdentity;

/**
 * @template-extends ScalarIdentity<string>
 */
class ProductId extends GenericScalarIdentity
{
    public static function from(string $id): self
    {
        return new self($id);
    }
}
