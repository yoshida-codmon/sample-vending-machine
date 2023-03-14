<?php

use Domain\Model\ChangeCash;
use Domain\Model\CoinInventory;
use Infrastructure\Repository\InMemoryCoinInventoryRepository;
use PHPUnit\Framework\TestCase;

class InMemoryCoinInventoryRepositoryTest extends TestCase
{
    public function testLoad()
    {
        $repository = new InMemoryCoinInventoryRepository();

        // load - 初期値は空
        $inventory = $repository->load();
        $this->assertEquals(0, $inventory->sum());
    }

    public function testSave()
    {
        // given
        $repository = new InMemoryCoinInventoryRepository();
        $expected = new CoinInventory();
        $cash = new ChangeCash([
            500 => 1,
            100 => 2,
             50 => 3,
             10 => 4
        ]);
        $expected->add($cash);

        // when
        $repository->save($expected);
        $actual = $repository->load();

        // then
        $this->assertTrue($expected === $actual);
    }
}