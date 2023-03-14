<?php

namespace UseCase\Purchase;

use Domain\Model\ChangeCash;
use Domain\Model\ReceiveCash;
use Domain\Repository\CoinInventoryRepository;
use Domain\Repository\ProductStockRepository;
use Domain\Service\PurchaseService;
use Domain\Value\Error\InsufficientPaidAmountException;
use Domain\Value\Error\MoneyShortageException;
use Domain\Value\Error\NotFoundProductException;
use Domain\Value\Error\OutOfStockException;

final class PurchaseUseCase
{
    public function __construct(
        private readonly ProductStockRepository $productStockRepository,
        private readonly CoinInventoryRepository $moneyInventoryRepository,
        private readonly PurchaseService $purchaseService,
    )
    {
    }

    /**
     * @param PurchaseInput $input
     * @return ChangeCash お釣りの硬貨
     * @throws NotFoundProductException
     * @throws OutOfStockException
     * @throws InsufficientPaidAmountException
     * @throws MoneyShortageException
     */
    public function handle(PurchaseInput $input): ChangeCash
    {
        // 商品在庫
        $stocks = $this->productStockRepository->load();

        // お釣りの金庫
        $coinInventory = $this->moneyInventoryRepository->load();

        // 購入処理
        $change = $this->purchaseService->purchase(
            $input->product, $input->receiveCash, $stocks, $coinInventory
        );

        // 保存する
        $this->productStockRepository->save($stocks);
        $this->moneyInventoryRepository->save($coinInventory);

        return $change;
    }
}
