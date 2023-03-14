<?php

use Domain\Model\ChangeCash;
use Domain\Value\Error\NotSupportedCashTypeException;
use PHPUnit\Framework\TestCase;

class ChangeCashTest extends TestCase
{
    public function testConstructor()
    {
        $cash = new ChangeCash([
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
        new ChangeCash([
            10000 => 1,
        ]);
    }

    public function testValidation_5000()
    {
        $this->expectException(NotSupportedCashTypeException::class);
        new ChangeCash([
            5000 => 1,
        ]);
    }

    public function testValidation_5()
    {
        $this->expectException(NotSupportedCashTypeException::class);
        new ChangeCash([
            5 => 1,
        ]);
    }

    public function testValidation_1()
    {
        $this->expectException(NotSupportedCashTypeException::class);
        new ChangeCash([
            1 => 1,
        ]);
    }
}
