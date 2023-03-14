<?php
declare(strict_types=1);

namespace Domain\Value\CashCollection;

use Domain\Value\Error\MoneyShortageException;
use Domain\Value\Error\NotSupportedCashTypeException;

class MutableCashCollection extends CashCollection implements IMutableCashCollection
{
    public function add(ICashCollection $cash): void
    {
        if (!$this->isAcceptable($cash)) {
            throw new NotSupportedCashTypeException();
        }

        foreach ($cash as $type => $count) {
            if (array_key_exists($type, $this->collection)) {
                $this->collection[$type] += $count;
            } else {
                $this->collection[$type] = $count;
            }
        }
    }

    public function subtract(mixed $cash): void
    {
        if (!$this->isAcceptable($cash)) {
            throw new NotSupportedCashTypeException();
        }

        if (!$this->includes($cash)) {
            throw new MoneyShortageException();
        }
        foreach ($cash as $type => $count) {
            $this->collection[$type] -= $count;
            if ($this->collection[$type] === 0) {
                unset($this->collection[$type]);
            }
        }
    }
}
