<?php
declare(strict_types=1);

namespace Domain\Service;

use Domain\Model\CoinInventory;
use Domain\Model\ProductStock\ProductId;
use Domain\Model\ProductStock\ProductStocks;
use Domain\Value\Error\InsufficientPaidAmountException;
use Domain\Value\Error\MoneyShortageException;
use Domain\Value\CashCollection\CashCollection;
use Domain\Value\CashCollection\CoinCollection;
use Domain\Value\CashCollection\ICashCollection;
use Domain\Value\Error\NotFoundProductException;
use Domain\Value\Error\OutOfStockException;

class PurchaseService
{
    /**
     * @param string $product
     * @param ICashCollection $paid
     * @param ProductStocks $stocks
     * @param CoinInventory $coinInventory
     * @return ICashCollection
     * @throws InsufficientPaidAmountException
     * @throws MoneyShortageException
     * @throws NotFoundProductException
     * @throws OutOfStockException
     */
    public function purchase(string $product, ICashCollection $paid, ProductStocks $stocks, CoinInventory $coinInventory): ICashCollection
    {
        // 商品在庫を検索する
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

        // 投入されたお金もお釣り用に使えるため、手持ちとしては合算しておく
        $cash = new CashCollection();
        $cash->add($paid);
        $cash->add($coinInventory);

        // お釣り計算 (釣り銭不足は例外になる)
        $change = $this->calculateChange($price, $paid, $cash, CoinCollection::class);

        // 在庫減少
        $stock->decrease();

        // 釣り銭の金庫からお金を引く
        $coinInventory->subtract($change);

        return $change;
    }

    /**
     * @param int $price
     * @param ICashCollection $paid
     * @param ICashCollection $inventory
     * @param class-string<ICashCollection> $collectionClass
     * @return ICashCollection
     * @throws MoneyShortageException
     * @throws InsufficientPaidAmountException
     */
    public function calculateChange(
        int $price, ICashCollection $paid, ICashCollection $inventory,
    ): ICashCollection
    {
        // 投入金額の合計額
        $amountPaid = $paid->sum();

        // 金額不足のエラーチェック
        if ($amountPaid < $price) {
            throw new InsufficientPaidAmountException();
        }

        // 釣り銭計算のために額面で降順ソート
        $coinTypes = array_map(
            function($type) { return $type->value(); },
            $inventory->availableTypes()
        );
        arsort($coinTypes);

        $changeCoins = [];

        $change = $amountPaid - $price;
        if ($change > 0) {
            foreach ($inventory as $coinType => $coinCount) {
                $count = (int) floor($change / $coinType);
                $count = min($coinCount, $count);
                if ($count > 0) {
                    $changeCoins[$coinType] = $count;
                    $change -= $coinType * $count;
                }
            }
            if ($change > 0) {
                throw new MoneyShortageException();
            }
        }

        return new CashCollection($changeCoins);
    }
}
