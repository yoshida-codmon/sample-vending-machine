<?php

require_once(__DIR__ . "/../vendor/autoload.php");
require_once(__DIR__ . "/../src/Main.php");

use PHPUnit\Framework\TestCase;

class MainTest extends TestCase
{
    public const COLA = 'cola'; // コーラ120円
    public const COFFEE = 'coffee'; // コーヒー150円
    public const ENERGY_DRINK = 'energy_drink'; // エナジードリンク210円

    public const NO_CHANGE = 'nochange'; // おつりなし

    // 120円支払ってコーラを買う
    public const INPUT_A = ['coins' => [
      '100' => 1,
      '10' => 2,
    ],
    'menu' => MainTest::COLA];

    // 200円支払ってコーヒーを買う
    public const INPUT_B = ['coins' => [
      '100' => 2,
    ],
    'menu' => MainTest::COFFEE];

    // 1000円支払ってエナジードリンクを買う
    public const INPUT_C = ['coins' => [
      '1000' => 1,
    ],
    'menu' => MainTest::ENERGY_DRINK];

    // ほぼ無限に硬貨が補充されている
    public const VENDING_MACHINE_COINS_INF = [
        '500' => 999,
        '100' => 999,
        '50' => 999,
        '10' => 999,
      ];

    /**
     * @group round1
     * @dataProvider provider_もっとも通常のパターン
     */
    public function test_round1_もっとも通常のパターン($coins, $menu, $expectedChange)
    {
        // Given: provider
        // When
        $actual = Main::runSimply($coins, $menu);

        // Then
        $this->assertSame($actual, $expectedChange);
    }

    public function provider_もっとも通常のパターン()
    {
        // $coins, $menu, $expectedChange
        return [
            "120円支払ってコーラを買う" => [MainTest::INPUT_A["coins"], MainTest::INPUT_A["menu"], MainTest::NO_CHANGE],
            "200円支払ってコーヒーを買う" => [MainTest::INPUT_B["coins"], MainTest::INPUT_B["menu"], "50 1"],
            "1000円支払ってエナジードリンクを買う" => [MainTest::INPUT_C["coins"], MainTest::INPUT_C["menu"], "500 1 100 2 50 1 10 4"],
            // テストケース追加のPR募集中
        ];
    }

    /**
     * @group round2
     * @dataProvider provider_自動販売機の硬貨の枚数を考慮するパターン
     */
    public function test_round2_自動販売機の硬貨の枚数を考慮するパターン($vendingMachineCoins, $userInput, $expectedChange)
    {
        // Given: provider
        // When
        $actual = Main::run($vendingMachineCoins, $userInput);

        // Then
        $this->assertSame($actual, $expectedChange);
    }

    public function provider_自動販売機の硬貨の枚数を考慮するパターン()
    {
        $vendingMachineCoins_scenario2 = [
            '500' => 999,
            '100' => 999,
            '50' => 0,
            '10' => 999,
        ];
        $userInput_scenario3 = [
            "coins" => [
                '100' => 1,
                '10' => 10,
            ],
            "menu" => MainTest::COLA,
        ];
        return [
            "制約なくおつりが払える" => [MainTest::VENDING_MACHINE_COINS_INF, MainTest::INPUT_C, "500 1 100 2 50 1 10 4"],
            "50円玉が切れている場合10円玉でおつりを払う" => [$vendingMachineCoins_scenario2, MainTest::INPUT_B, "10 5"],
            // 応用なのでコメントアウト。こういうドメイン知識もきっとあるだろう
            // "両替目的の購入に応じない" => [MainTest::VENDING_MACHINE_COINS_INF, $userInput_scenario3, "10 8"],
            // テストケース追加のPR募集中
        ];
    }
}
