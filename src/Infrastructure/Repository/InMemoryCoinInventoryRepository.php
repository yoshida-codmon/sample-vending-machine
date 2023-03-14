<?php

namespace Infrastructure\Repository;

use Domain\Model\CoinInventory;
use Domain\Repository\CoinInventoryRepository;

class InMemoryCoinInventoryRepository implements CoinInventoryRepository
{
    public function __construct(
        private CoinInventory $inventory = new CoinInventory()
    )
    {
    }

    public function load(): CoinInventory
    {
        return $this->inventory;
    }

    public function save(CoinInventory $inventory): void
    {
        $this->inventory = $inventory;
    }
}