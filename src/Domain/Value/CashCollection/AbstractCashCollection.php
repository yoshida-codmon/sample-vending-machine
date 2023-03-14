<?php
declare(strict_types=1);

namespace Domain\Value\CashCollection;

use ArrayIterator;
use Domain\Value\Error\MoneyShortageException;
use Domain\Value\Error\NotSupportedMoneyTypeException;
use Domain\Value\Money\CashType;
use Domain\Value\Money\ICashType;
use Traversable;

abstract class AbstractCashCollection implements ICashCollection
{
    /**
     * @var array<int, int>
     */
    protected array $collection = [];

    /**
     * @param $coinCountMap array
     */
    public function __construct(array $coinCountMap = [])
    {
        $types = $this->validTypes();
        foreach ($coinCountMap as $money => $count) {
            $type = CashType::valueOf($money);
            if (in_array($type, $types)) {
                $this->collection[$money] = $count;
            } else {
                throw new NotSupportedMoneyTypeException();
            }
        }
    }

    /**
     * @return ICashType[]
     */
    public abstract function validTypes(): array;

    private function validate(ICashCollection $money): void
    {
        $validTypes = $this->validTypes();
        $types = $money->availableTypes();
        foreach ($types as $type) {
            if (!in_array($type, $validTypes)) {
                throw new NotSupportedMoneyTypeException();
            }
        }
    }

    public function add(ICashCollection $money): void
    {
        $this->validate($money);

        foreach ($money as $type => $count) {
            if (array_key_exists($type, $this->collection)) {
                $this->collection[$type] += $count;
            } else {
                $this->collection[$type] = $count;
            }
        }
    }

    public function subtract(mixed $money): void
    {
        $this->validate($money);
        if (!$this->includes($money)) {
            throw new MoneyShortageException();
        }
        foreach ($money as $type => $count) {
            $this->collection[$type] -= $count;
        }
    }

    public function includes(ICashCollection $money): bool
    {
        foreach ($money as $type => $count) {
            if (!array_key_exists($type, $this->collection) || $this->collection[$type] < $count) {
                return false;
            }
        }
        return true;
    }

    public function sum(): int
    {
        $collection = $this->collection;
        $types = array_keys($collection);
        return array_reduce($types, function ($paid, $coin) use($collection) {
            return $paid + $coin * $collection[$coin];
        }, 0);
    }

    public function availableTypes(): array
    {
        $types = [];
        foreach ($this->collection as $type => $count) {
            if ($count > 0) {
                $types[] = CashType::valueOf($type);
            }
        }
        return $types;
    }

    /**
     * @return Traversable<int, int>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->collection);
    }
}
