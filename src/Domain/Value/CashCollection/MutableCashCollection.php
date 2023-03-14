<?php
declare(strict_types=1);

namespace Domain\Value\CashCollection;

use ArrayIterator;
use Domain\Value\CashType\CashType;
use Domain\Value\CashType\ICashType;
use Domain\Value\Error\MoneyShortageException;
use Domain\Value\Error\NotSupportedCashTypeException;
use InvalidArgumentException;
use Traversable;

class MutableCashCollection extends CashCollection implements IMutableCashCollection
{
    public function add(ICashCollection $cash): void
    {
        $this->validate($cash);

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
        $this->validate($cash);
        if (!$this->includes($cash)) {
            throw new MoneyShortageException();
        }
        foreach ($cash as $type => $count) {
            $this->collection[$type] -= $count;
        }
    }
}
