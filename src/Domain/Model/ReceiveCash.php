<?php
declare(strict_types=1);

namespace Domain\Model;

use Domain\Value\CashCollection\CashCollection;
use Domain\Value\CashType\BillType;
use Domain\Value\CashType\CoinType;

class ReceiveCash extends CashCollection
{
    /**
     * @inheritDoc
     */
    protected function validTypes(): array
    {
        return [
            BillType::BILL_1000,
            CoinType::COIN_500,
            CoinType::COIN_100,
            CoinType::COIN_50,
            CoinType::COIN_10,
        ];
    }
}
