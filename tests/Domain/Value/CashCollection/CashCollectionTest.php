<?php

namespace CashCollection;

use Domain\Value\CashCollection\CashCollection;
use Domain\Value\CashType\CoinType;
use Domain\Value\Error\NotSupportedCashTypeException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CashCollectionTest extends TestCase
{
    public function testConstructor_NotSupportedCashTypeException()
    {
        $this->expectException(NotSupportedCashTypeException::class);

        new CashCollection([
            20 => 1  // 存在しない硬貨
        ]);
    }

    public function testConstructor_InvalidArgumentException1()
    {
        $this->expectException(InvalidArgumentException::class);

        new CashCollection([
            500 => -1  // 枚数として不正な値
        ]);
    }

    public function testConstructor_InvalidArgumentException2()
    {
        $this->expectException(InvalidArgumentException::class);

        new CashCollection([
            500 => 'abc'  // 枚数として不正な値
        ]);
    }

    public function testIncludes()
    {
        // given
        $cash1 = new CashCollection([
            1000 => 1,
            500 => 2,
            100 => 3,
        ]);
        $cash2 = new CashCollection([
            1000 => 1,
            500 => 2,
        ]);
        // when, then
        $this->assertTrue($cash1->includes($cash2));
        $this->assertFalse($cash2->includes($cash1));
    }

    public function testIncludes_empty()
    {
        // given
        $cash1 = new CashCollection([1000 => 1]);
        $cash2 = new CashCollection();
        // when, then
        $this->assertTrue($cash1->includes($cash2));
        $this->assertFalse($cash2->includes($cash1));
    }

    public function testSum()
    {
        // given
        $cash = new CashCollection([
            1000 => 1,
            500 => 2,
            100 => 10,
            50 => 20
        ]);
        // when, then
        $this->assertEquals(4000, $cash->sum());
    }

    public function testSum_empty()
    {
        // given
        $cash = new CashCollection();
        // when, then
        $this->assertEquals(0, $cash->sum());
    }

    public function testAvailableTypes()
    {
        // given
        $cash = new CashCollection([
            500 => 1,
            50 => 2,
            5 => 3,
        ]);
        // when, then
        $this->assertEquals(
            [
                CoinType::COIN_500,
                CoinType::COIN_50,
                CoinType::COIN_5,
            ],
            $cash->availableTypes()
        );
    }

    public function testAvailableTypes_empty()
    {
        // given
        $cash = new CashCollection();
        // when, then
        $this->assertEquals([], $cash->availableTypes());
    }

    public function testIsAcceptable_true()
    {
        // given
        $cash1 = new class extends CashCollection {
            protected function validTypes(): array
            {
                return [CoinType::COIN_500]; // only 500 yen
            }
        };
        $cash2 = new CashCollection([500 => 5]);
        // when, then
        $this->assertTrue($cash1->isAcceptable($cash2));
    }

    public function testIsAcceptable_false()
    {
        // given
        $cash1 = new class extends CashCollection {
            protected function validTypes(): array
            {
                return [CoinType::COIN_500]; // only 500 yen
            }
        };
        $cash2 = new CashCollection([
            500 => 5,
            100 => 5
        ]);
        // when, then
        $this->assertFalse($cash1->isAcceptable($cash2));
    }

    public function testIsAcceptable_empty()
    {
        // given
        $cash1 = new CashCollection();
        $cash2 = new CashCollection();
        // when, then
        $this->assertTrue($cash1->isAcceptable($cash2));
    }
}