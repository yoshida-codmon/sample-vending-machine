<?php
declare(strict_types=1);

namespace Presentation;

use Domain\Model\ReceiveCash;
use Domain\Repository\CoinInventoryRepository;
use Domain\Repository\ProductStockRepository;
use Domain\Service\PurchaseService;
use Domain\Value\CashCollection\ICashCollection;
use Domain\Value\Error\InsufficientPaidAmountException;
use Domain\Value\Error\MoneyShortageException;
use Domain\Value\Error\NotFoundProductException;
use Domain\Value\Error\OutOfStockException;
use UseCase\Purchase\PurchaseInput;
use UseCase\Purchase\PurchaseUseCase;

class TextController
{
    public function __construct(
        private readonly ProductStockRepository $productStockRepository,
        private readonly CoinInventoryRepository $coinInventoryRepository,
    )
    {
    }

    /**
     * @param array $coins
     * @param string $menu
     * @return string
     */
    public function run(array $coins, string $menu): string
    {
        // ユースケース
        $useCase = new PurchaseUseCase(
            $this->productStockRepository,
            $this->coinInventoryRepository,
            new PurchaseService(),
        );

        try {
            // 購入・お釣りの計算
            $input = new PurchaseInput(
                $menu,
                new ReceiveCash($coins) // 受け取ったお金
            );
            $changeCoins = $useCase->handle($input);

            // 出力の書式をフォーマットする
            return $this->formatCoins($changeCoins);

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

    private function formatCoins(ICashCollection $coins): string
    {
        if ($coins->sum() === 0) {
            return 'nochange';
        }

        $results = [];
        foreach ($coins as $coin => $count) {
            $results[] = "$coin $count";
        }
        return implode(' ', $results);
    }
}