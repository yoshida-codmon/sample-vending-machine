<?php
declare(strict_types=1);

namespace Domain\Value\CashType;

interface ICashType
{
    public function value(): int;
}
