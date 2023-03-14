<?php
declare(strict_types=1);

namespace Domain\Value\CashType;

enum CoinType: int implements ICashType
{
    case COIN_500 = 500;
    case COIN_100 = 100;
    case COIN_50 = 50;
    case COIN_10 = 10;
    case COIN_1 = 1;

    public const Coin_500 = 500;
    public const Coin_100 = 100;
    public const Coin_50 = 50;
    public const Coin_10 = 10;

    public function value(): int
    {
        return $this->value;
    }
}
