<?php
declare(strict_types=1);

namespace Domain\Value\CashCollection;

use Domain\Value\Error\MoneyShortageException;
use Domain\Value\CashType\ICashType;
use IteratorAggregate;

interface ICashCollection extends IteratorAggregate
{
    /**
     * @param ICashCollection $money
     * @return bool
     */
    public function includes(ICashCollection $money): bool;

    /**
     * @return int
     */
    public function sum(): int;

    /**
     * @return ICashType[]
     */
    public function availableTypes(): array;

    /**
     * @param ICashCollection $cash
     * @return bool
     */
    public function isAcceptable(ICashCollection $cash): bool;
}
