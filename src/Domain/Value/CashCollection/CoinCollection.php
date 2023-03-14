<?php
declare(strict_types=1);

namespace Domain\Value\CashCollection;

use Domain\Value\Money\CoinType;

class CoinCollection extends AbstractCashCollection
{
    /**
     * @inheritDoc
     */
    public function validTypes(): array
    {
        return CoinType::cases();
    }
}
