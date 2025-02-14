<?php

declare(strict_types=1);

use Domain\Model\CoinInventory;
use Domain\Model\ProductStock\ProductStock;
use Domain\Model\ProductStock\ProductStocks;
use Infrastructure\Repository\InMemoryCoinInventoryRepository;
use Infrastructure\Repository\InMemoryProductStockRepository;
use Presentation\TextController;


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
        ['coins' => $coins, 'menu' => $menu] = $userInput;

        // 商品および在庫
        $stocks = new ProductStocks();
        $stocks->add(ProductStock::create('cola', 120, 30));
        $stocks->add(ProductStock::create('coffee', 150, 30));
        $stocks->add(ProductStock::create('energy_drink', 210, 30));
        $productStockRepository = new InMemoryProductStockRepository();
        $productStockRepository->save($stocks);

        // お釣り用のお金
        $coinInventory = new CoinInventory($vendingMachineCoins);
        $coinInventoryRepository = new InMemoryCoinInventoryRepository();
        $coinInventoryRepository->save($coinInventory);

        $controller = new TextController(
            $productStockRepository,
            $coinInventoryRepository
        );
        return $controller->run($coins, $menu);
    }
}
