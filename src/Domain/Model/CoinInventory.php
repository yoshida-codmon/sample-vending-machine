<?php
declare(strict_types=1);

namespace Domain\Model;

use Domain\Value\CashCollection\MutableCashCollection;
use Domain\Value\CashType\BillType;
use Domain\Value\CashType\CoinType;

class CoinInventory extends MutableCashCollection
{
    // 5円、1円は使用できない
    public static array $SUPPORTED_COIN_TYPES = [
        BillType::BILL_1000, // 受け取り額を釣り銭用に含めるために入れている
        CoinType::COIN_500,
        CoinType::COIN_100,
        CoinType::COIN_50,
        CoinType::COIN_10,
    ];

    protected function validTypes(): array
    {
        return self::$SUPPORTED_COIN_TYPES;
    }
}
