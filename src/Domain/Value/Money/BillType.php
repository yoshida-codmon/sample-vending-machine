<?php
declare(strict_types=1);

namespace Domain\Value\Money;

enum BillType: int implements ICashType
{
    case BILL_10000 = 10000;
    case BILL_5000 = 5000;
    case BILL_2000 = 2000;
    case BILL_1000 = 1000;

    public const Bill_10000 = 10000;
    public const Bill_5000 = 5000;
    public const Bill_2000 = 2000;
    public const Bill_1000 = 1000;

    public function value(): int
    {
        return $this->value;
    }
}
