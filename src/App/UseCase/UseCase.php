<?php

namespace App\UseCase;

use Domain\Model\ProductStock\ProductId;
use Domain\Repository\CoinInventoryRepository;
use Domain\Repository\ProductStockRepository;
use Domain\Service\PurchaseService;
use Domain\Value\Error\InsufficientPaidAmountException;
use Domain\Value\Error\MoneyShortageException;
use Domain\Value\Error\NotFoundProductException;
use Domain\Value\Error\OutOfStockException;
use Domain\Value\CashCollection\CoinCollection;
use Domain\Value\CashCollection\ICashCollection;

final class UseCase
{
    public function __construct(
        private readonly ProductStockRepository $productStockRepository,
        private readonly CoinInventoryRepository $moneyInventoryRepository
    )
    {
    }

    /**
     * @param string $product 購入する商品(商品ID文字列)
     * @param ICashCollection $paid 支払ったコイン構成
     * @return ICashCollection お釣りの硬貨
     * @throws NotFoundProductException
     * @throws OutOfStockException
     * @throws InsufficientPaidAmountException
     * @throws MoneyShortageException
     */
    public function purchase(string $product, ICashCollection $paid): ICashCollection
    {
        // 商品在庫
        $stocks = $this->productStockRepository->load();

        // お釣りの金庫
        $coinInventory = $this->moneyInventoryRepository->load();

        // 購入処理
        $calculator = new PurchaseService();
        return $calculator->purchase($product, $paid, $stocks, $coinInventory);
    }
}
