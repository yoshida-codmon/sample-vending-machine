<?php
declare(strict_types=1);

namespace Domain\Value\CashCollection;

use Domain\Value\Error\MoneyShortageException;
use Domain\Value\Money\ICashType;
use IteratorAggregate;

interface ICashCollection extends IteratorAggregate
{
    /**
     * @param ICashCollection $money
     * @return void
     */
    public function add(ICashCollection $money): void;

    /**
     * @param ICashCollection $money
     * @return void
     * @throws MoneyShortageException
     */
    public function subtract(ICashCollection $money): void;

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
     * @return ICashType[]
     */
    public function validTypes(): array;
}
