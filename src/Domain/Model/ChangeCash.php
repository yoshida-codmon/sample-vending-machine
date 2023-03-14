<?php
declare(strict_types=1);

namespace Domain\Model;

use Domain\Value\CashCollection\CashCollection;

class ChangeCash extends CashCollection
{
    /**
     * @inheritDoc
     */
    protected function validTypes(): array
    {
        return CoinInventory::$SUPPORTED_COIN_TYPES;
    }
}
