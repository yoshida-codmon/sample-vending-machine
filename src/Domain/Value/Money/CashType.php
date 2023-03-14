<?php
declare(strict_types=1);

namespace Domain\Value\Money;

use Domain\Value\Error\NotSupportedMoneyTypeException;

class CashType {
    public static function valueOf(int $value): ICashType
    {
        $moneyType = CoinType::tryFrom($value) ?? BillType::tryFrom($value);
        if ($moneyType) {
            return $moneyType;
        }

        throw new NotSupportedMoneyTypeException();
    }

    /**
     * @return ICashType[]
     */
    public static function cases(): array
    {
        return array_merge(BillType::cases(), CoinType::cases());
    }
}
