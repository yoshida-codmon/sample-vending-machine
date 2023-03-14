<?php

namespace Domain\Repository;

use Domain\Model\CoinInventory;

interface CoinInventoryRepository
{
    public function load(): CoinInventory;

    public function save(CoinInventory $inventory): void;
}
