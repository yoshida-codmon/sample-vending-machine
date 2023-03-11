<?php

declare(strict_types=1);

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
        // - お釣り用のお金
        $moneyInventory = [];
        foreach ($vendingMachineCoins as $key => $value) {
            $moneyInventory[(int)$key] = $value;
        }
        $coins = $userInput['coins'];
        $menu = $userInput['menu'];

        // 料金表から商品の料金を検索する
        // - 料金表
        $menuPriceMap = [
            'cola' => 120,
            'coffee' => 150,
            'energy_drink' => 210
        ];

        if (!array_key_exists($menu, $menuPriceMap)) {
            return "error: Not found in the menu list. [$menu]";
        }
        $price = $menuPriceMap[$menu];

        // お釣りを計算する
        // - 投入金額の合計額を計算
        $paid = array_reduce(array_keys($coins), function ($paid, $coin) use($coins) {
            return $paid + (int) $coin * $coins[$coin];
        }, 0);

        // - 金額不足のエラーチェック
        if ($paid < $price) {
            return "error: Insufficient paid amount";
        }

        $coinTypes = array_keys($moneyInventory);
        arsort($coinTypes);

        $change = $paid - $price;
        if ($change === 0) {
            return 'nochange';
        }

        $changeCoins = [];
        foreach ($coinTypes as $coinType) {
            $count = (int) floor($change / $coinType);
            $count = min($moneyInventory[$coinType], $count);
            if ($count > 0) {
                $changeCoins[$coinType] = $count;
                $change -= $coinType * $count;
            }
        }

        if ($change > 0) {
            return 'error: Sorry,we dont have any change';
        }

        // 出力の書式をフォーマットする
        $results = [];
        foreach ($changeCoins as $coin => $count) {
            $results[] = "$coin $count";
        }

        return implode(' ', $results);
    }
}
