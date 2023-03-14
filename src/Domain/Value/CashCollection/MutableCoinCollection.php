<?php
declare(strict_types=1);

namespace Domain\Value\CashCollection;

use Domain\Value\CashType\CoinType;

class MutableCoinCollection extends MutableCashCollection
{
    /**
     * @inheritDoc
     */
    protected function validTypes(): array
    {
        return CoinType::cases();
    }
}
