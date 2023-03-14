<?php
declare(strict_types=1);

namespace Domain\Value\Money;

interface ICashType
{
    public function value(): int;
}
