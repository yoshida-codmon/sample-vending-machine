<?php
declare(strict_types=1);

namespace Domain\Value\CashCollection;

use ArrayIterator;
use Domain\Value\CashType\CashType;
use Domain\Value\CashType\ICashType;
use Domain\Value\Error\NotSupportedCashTypeException;
use InvalidArgumentException;
use Traversable;

class CashCollection implements ICashCollection
{
    /**
     * @var array
     */
    protected array $collection = [];

    /**
     * @param $coinCountMap array|ICashCollection
     * @throws InvalidArgumentException
     * @throws NotSupportedCashTypeException
     */
    public function __construct(array|ICashCollection $coinCountMap = [])
    {
        $types = $this->validTypes();

        foreach ($coinCountMap as $cashType => $count) {
            $type = CashType::valueOf($cashType);
            if ($count < 0 || !is_int($count)) {
                throw new InvalidArgumentException(
                    "The number of '$cashType' units must be greater than or equal to 0."
                );
            }
            if (in_array($type, $types)) {
                $this->collection[$cashType] = $count;
            } else {
                throw new NotSupportedCashTypeException();
            }
        }
    }

    /**
     * @return ICashType[]
     */
    protected function validTypes(): array
    {
        return CashType::cases();
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
        return array_reduce($types, function ($paid, $coin) use ($collection) {
            return $paid + $coin * $collection[$coin];
        }, 0);
    }

    /**
     * @inheritDoc
     */
    public function isAcceptable(ICashCollection $cash): bool
    {
        $validTypes = $this->validTypes();
        $types = $cash->availableTypes();
        foreach ($types as $type) {
            if (!in_array($type, $validTypes)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return ICashType[]
     */
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
