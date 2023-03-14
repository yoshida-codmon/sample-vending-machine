<?php

namespace Domain\Service;

use Domain\Value\Error\InsufficientPaidAmountException;
use Domain\Value\Error\MoneyShortageException;
use Domain\Value\CashCollection\CashCollection;
use Domain\Value\CashCollection\CoinCollection;
use Domain\Value\CashCollection\ICashCollection;

class ChangeCalculator
{
    /**
     * @param int $price
     * @param ICashCollection $paid
     * @param ICashCollection $inventory
     * @param class-string<ICashCollection> $collectionClass
     * @return CoinCollection
     * @throws MoneyShortageException
     * @throws InsufficientPaidAmountException
     */
    public function calculate(
        int    $price, ICashCollection $paid, ICashCollection $inventory,
        string $collectionClass = CashCollection::class
    ): CoinCollection
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

        return new $collectionClass($changeCoins);
    }
}
