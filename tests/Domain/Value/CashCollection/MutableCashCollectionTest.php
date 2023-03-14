<?php

namespace CashCollection;

use Domain\Value\CashCollection\CashCollection;
use Domain\Value\CashCollection\MutableCashCollection;
use Domain\Value\Error\MoneyShortageException;
use PHPUnit\Framework\TestCase;

class MutableCashCollectionTest extends TestCase
{
    public function testAdd()
    {
        // given
        $cash1 = new MutableCashCollection([
            1000 => 1,
            500 => 1,
        ]);
        $cash2 = new CashCollection([
            500 => 1,
            100 => 3,
        ]);
        // when
        $cash1->add($cash2);
        // then
        $expected = new MutableCashCollection([
            1000 => 1,
            500 => 2,
            100 => 3,
        ]);

        $this->assertEquals($expected, $cash1);
    }

    public function testAdd_empty()
    {
        // given
        $cash1 = new MutableCashCollection();
        $cash2 = new CashCollection();
        // when
        $cash1->add($cash2);
        // then
        $expected = new MutableCashCollection();

        $this->assertEquals($expected, $cash1);
    }

    /**
     * @throws MoneyShortageException
     */
    public function testSubtract()
    {
        // given
        $cash1 = new MutableCashCollection([
            1000 => 1,
            500 => 2,
        ]);
        $cash2 = new CashCollection([
            1000 => 1,
            500 => 1,
        ]);
        // when
        $cash1->subtract($cash2);
        // then
        $expected = new MutableCashCollection([
            500 => 1,
        ]);
        $this->assertEquals($expected, $cash1);
    }

    /**
     * @throws MoneyShortageException
     */
    public function testSubtract_empty()
    {
        // given
        $cash1 = new MutableCashCollection();
        $cash2 = new CashCollection();
        // when
        $cash1->subtract($cash2);
        // then
        $expected = new MutableCashCollection();
        $this->assertEquals($expected, $cash1);
    }

    /**
     * @throws MoneyShortageException
     */
    public function testSubtract_MoneyShortageException()
    {
        $this->expectException(MoneyShortageException::class);
        // given
        $cash1 = new MutableCashCollection([
            1000 => 1,
            500 => 2,
        ]);
        $cash2 = new CashCollection([
            1000 => 2,
        ]);
        // when
        $cash1->subtract($cash2);
    }
}