<?php

use Domain\Model\ReceiveCash;
use Domain\Value\Error\NotSupportedCashTypeException;
use PHPUnit\Framework\TestCase;

class ReceiveCashTest extends TestCase
{
    public function testConstructor()
    {
        $cash = new ReceiveCash([
            1000 => 1,
             500 => 1,
             100 => 5,
              50 => 10,
              10 => 50,
        ]);
        $this->assertEquals(3000, $cash->sum());
    }

    public function testValidation_10000()
    {
        $this->expectException(NotSupportedCashTypeException::class);
        new ReceiveCash([
            10000 => 1,
        ]);
    }

    public function testValidation_5000()
    {
        $this->expectException(NotSupportedCashTypeException::class);
        new ReceiveCash([
            5000 => 1,
        ]);
    }

    public function testValidation_5()
    {
        $this->expectException(NotSupportedCashTypeException::class);
        new ReceiveCash([
            5 => 1,
        ]);
    }

    public function testValidation_1()
    {
        $this->expectException(NotSupportedCashTypeException::class);
        new ReceiveCash([
            1 => 1,
        ]);
    }
}
