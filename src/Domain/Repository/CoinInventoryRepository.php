<?php
declare(strict_types=1);

namespace Domain\Repository;

use Domain\Model\CoinInventory;

interface CoinInventoryRepository
{
    public function load(): CoinInventory;

    public function save(CoinInventory $inventory): void;
}
