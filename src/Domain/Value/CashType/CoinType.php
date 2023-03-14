<?php
declare(strict_types=1);

namespace Domain\Value\CashType;

enum CoinType: int implements ICashType
{
    case COIN_500 = 500;
    case COIN_100 = 100;
    case COIN_50 = 50;
    case COIN_10 = 10;
    case COIN_5 = 5;
    case COIN_1 = 1;

    public function value(): int
    {
        return $this->value;
    }
}
