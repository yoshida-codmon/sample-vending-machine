<?php
declare(strict_types=1);

namespace Domain\Value\CashCollection;

use Domain\Value\Error\MoneyShortageException;

interface IMutableCashCollection extends ICashCollection
{
    /**
     * @param ICashCollection $cash
     * @return void
     */
    public function add(ICashCollection $cash): void;

    /**
     * @param ICashCollection $cash
     * @return void
     * @throws MoneyShortageException
     */
    public function subtract(ICashCollection $cash): void;
}
