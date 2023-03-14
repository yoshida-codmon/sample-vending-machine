<?php
declare(strict_types=1);

namespace Domain\Value\CashType;

enum BillType: int implements ICashType
{
    case BILL_10000 = 10000;
    case BILL_5000 = 5000;
    case BILL_2000 = 2000;
    case BILL_1000 = 1000;

    public function value(): int
    {
        return $this->value;
    }
}
