<?php

namespace App\UseCase;

use Domain\Model\ProductStock\ProductId;
use Domain\Repository\CoinInventoryRepository;
use Domain\Repository\ProductStockRepository;
use Domain\Service\ChangeCalculator;
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
     * @return CoinCollection お釣りの硬貨
     * @throws NotFoundProductException
     * @throws OutOfStockException
     * @throws InsufficientPaidAmountException
     * @throws MoneyShortageException
     */
    public function purchase(string $product, ICashCollection $paid): CoinCollection
    {
        // 商品在庫
        $stocks = $this->productStockRepository->load();

        $stock = $stocks->get(ProductId::from($product));
        if (!$stock) {
            // 商品が見つからない
            throw new NotFoundProductException();
        }
        if ($stock->isOutOfStock()) {
            // 在庫切れ
            throw new OutOfStockException();
        }

        // 商品の金額
        $price = $stock->product->price;

        // お釣りを計算
        $moneyInventory = $this->moneyInventoryRepository->load();
        $calculator = new ChangeCalculator();
        return $calculator->calculate($price, $paid, $moneyInventory, CoinCollection::class);
    }
}
