<?php
declare(strict_types=1);

namespace Domain\Value\CashCollection;

use Domain\Value\Money\CashType;

class CashCollection extends AbstractCashCollection
{
    /**
     * @inheritDoc
     */
    public function validTypes(): array
    {
        return CashType::cases();
    }
}
