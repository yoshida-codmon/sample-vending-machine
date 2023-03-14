<?php
declare(strict_types=1);

namespace Domain\Value\CashType;

use Domain\Value\Error\NotSupportedCashTypeException;

class CashType {
    public static function valueOf(int $value): ICashType
    {
        $cashType = CoinType::tryFrom($value) ?? BillType::tryFrom($value);
        if ($cashType) {
            return $cashType;
        }

        throw new NotSupportedCashTypeException();
    }

    /**
     * @return ICashType[]
     */
    public static function cases(): array
    {
        return array_merge(BillType::cases(), CoinType::cases());
    }
}
