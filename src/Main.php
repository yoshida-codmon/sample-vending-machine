<?php

declare(strict_types=1);

use App\UseCase\UseCase;
use Domain\Model\CoinInventory;
use Domain\Model\ProductStock\ProductStock;
use Domain\Model\ProductStock\ProductStocks;
use Domain\Value\Error\InsufficientPaidAmountException;
use Domain\Value\Error\MoneyShortageException;
use Domain\Value\Error\NotFoundProductException;
use Domain\Value\Error\OutOfStockException;
use Domain\Value\CashCollection\CashCollection;
use Infrastructure\Repository\InMemoryCoinInventoryRepository;
use Infrastructure\Repository\InMemoryProductStockRepository;


/**
 * メインクラス。
 * 原則ここにロジックは書かないこと。
 */
class Main
{
    /**
     * 処理の開始地点
     *
     * @param array $coins 投入額
     * @param string $menu 注文
     * @return string おつり。大きな硬貨順に枚数を並べる。なしの場合はnochange
     * ex.)
     * - 100円3枚、50円1枚、10円3枚なら"100 3 50 1 10 3"
     */
    public static function runSimply(array $coins, string $menu): string
    {
        // - お釣り用のお金
        $moneyInventory = [
            500 => 999,
            100 => 999,
            50 => 999,
            10 => 999,
        ];

        return self::run($moneyInventory, ['coins' => $coins, 'menu' => $menu]);
    }

    /**
     * 処理の開始地点。ただし自動販売機がいくつ硬貨を持っているかも合わせて処理する
     *
     * @param array $vendingMachineCoins 自販機に補充される硬貨
     * @param array $userInput 投入額と注文。前述の$coinsと$menuをあわせたもの
     * @return string おつり。大きな硬貨順に枚数を並べる。なしの場合はnochange
     * ex.)
     * - 100円3枚、50円1枚、10円3枚なら"100 3 50 1 10 3"
     */
    public static function run(array $vendingMachineCoins, array $userInput): string
    {
        // 受け取ったお金、商品
        [ 'coins' => $coins, 'menu' => $menu ] = $userInput;

        // お釣り用のお金
        $coinInventory = new CoinInventory($vendingMachineCoins);
        $coinInventoryRepository = new InMemoryCoinInventoryRepository();
        $coinInventoryRepository->save($coinInventory);

        // 商品および在庫
        $stocks = new ProductStocks();
        $stocks->add(ProductStock::create('cola', 120, 30));
        $stocks->add(ProductStock::create('coffee', 150, 30));
        $stocks->add(ProductStock::create('energy_drink', 210, 30));
        $productStockRepository = new InMemoryProductStockRepository();
        $productStockRepository->save($stocks);

        // ユースケース
        $useCase = new UseCase($productStockRepository, $coinInventoryRepository);

        // 受け取ったお金
        $paid = new CashCollection($coins);

        // 購入・お釣りの計算
        try {
            $changeCoins = $useCase->purchase($menu, $paid);
            if ($changeCoins->sum() === 0) {
                return 'nochange';
            }
            // 出力の書式をフォーマットする
            $results = [];
            foreach ($changeCoins as $coin => $count) {
                $results[] = "$coin $count";
            }
            return implode(' ', $results);

        } catch (NotFoundProductException $e) {
            return "error: Not found product in the menu list. [$menu]";
        } catch (OutOfStockException $e) {
            return "error: Out of stock. [$menu]";
        } catch (InsufficientPaidAmountException $e) {
            return 'error: Insufficient paid amount.';
        } catch (MoneyShortageException $e) {
            return 'error: Shortage of cash.';
        }
    }
}
