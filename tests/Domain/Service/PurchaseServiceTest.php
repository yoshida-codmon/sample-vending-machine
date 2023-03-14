<?php

namespace Service;

use Domain\Model\ChangeCash;
use Domain\Model\CoinInventory;
use Domain\Model\ProductStock\ProductId;
use Domain\Model\ProductStock\ProductStock;
use Domain\Model\ProductStock\ProductStocks;
use Domain\Model\ReceiveCash;
use Domain\Service\PurchaseService;
use Domain\Value\Error\InsufficientPaidAmountException;
use Domain\Value\Error\MoneyShortageException;
use Domain\Value\Error\NotFoundProductException;
use Domain\Value\Error\OutOfStockException;
use PHPUnit\Framework\TestCase;

class PurchaseServiceTest extends TestCase
{
    /**
     * @throws NotFoundProductException
     * @throws MoneyShortageException
     * @throws InsufficientPaidAmountException
     * @throws OutOfStockException
     */
    public function testPurchase()
    {
        // given
        $product = 'cola';
        $received = new ReceiveCash([ 1000 => 1 ]);
        $stocks = new ProductStocks();
        $stocks->add(ProductStock::create('cola', 150, 1));
        $inventory = new CoinInventory([
            500 => 10,
            100 => 10,
             50 => 10,
             10 => 10
        ]);

        // when
        $service = new PurchaseService();
        $change = $service->purchase($product, $received, $stocks, $inventory);

        // then
        // * 1000円で150円のものを購入してお釣りは850円
        $expected = new ChangeCash([
            500 => 1,
            100 => 3,
             50 => 1,
        ]);
        $this->assertEquals($expected, $change);
        $this->assertEquals(850, $change->sum());

        // * colaの在庫減少 (1 -> 0)
        $this->assertEquals(0, $stocks->get(ProductId::from('cola'))->getQuantity());

        // * 釣り銭の減少
        $expectedInventory = new CoinInventory([
            1000 => 1, // 受け取った分
            500 => 9, // 10 - 1
            100 => 7, // 10 - 3
             50 => 9, // 10 - 1
             10 => 10 // 10 - 0
        ]);
        $this->assertEquals($expectedInventory, $inventory);
    }

    /**
     * @throws NotFoundProductException
     * @throws MoneyShortageException
     * @throws InsufficientPaidAmountException
     * @throws OutOfStockException
     */
    public function testPurchase_購入金額もお釣りに使う()
    {
        // 150円の商品を550円で購入
        // 400円のうち、100円は50円玉で返却 (うち1枚は投入したもの)

        // given
        $product = 'cola';
        $received = new ReceiveCash([
            500 => 1,
             50 => 1
        ]);
        $stocks = new ProductStocks();
        $stocks->add(ProductStock::create('cola', 150, 1));
        $inventory = new CoinInventory([
            100 => 3, // お釣りは4枚必要だが不足 -> 50円玉が使われる
             50 => 1, // お釣りは3枚必要だが不足 -> 投入金額を期待
        ]);

        // when
        $service = new PurchaseService();
        $change = $service->purchase($product, $received, $stocks, $inventory);

        // then
        // * 450円のうち、150円は50円玉で返却 (うち1枚は投入したもの)
        $expected = new ChangeCash([
            100 => 3,
            50 => 2,
        ]);
        $this->assertEquals($expected, $change);
        $this->assertEquals(400, $change->sum());

        // * 釣り銭は投入された500円玉だけが残る
        $expectedInventory = new CoinInventory([500 => 1]);
        $this->assertEquals($expectedInventory, $inventory);
    }

    /**
     * @throws MoneyShortageException
     * @throws InsufficientPaidAmountException
     * @throws OutOfStockException
     */
    public function testPurchase_NotFoundProductException()
    {
        // 商品が存在しない
        $this->expectException(NotFoundProductException::class);

        // given
        $product = 'not found';
        $received = new ReceiveCash([100 => 1]);
        $stocks = new ProductStocks();
        $stocks->add(ProductStock::create('cola', 100, 1));
        $inventory = new CoinInventory([500 => 1]);

        // when
        $service = new PurchaseService();
        $service->purchase($product, $received, $stocks, $inventory);
    }

    /**
     * @throws InsufficientPaidAmountException
     * @throws OutOfStockException
     * @throws NotFoundProductException
     */
    public function testPurchase_MoneyShortageException()
    {
        // 釣り銭切れ
        $this->expectException(MoneyShortageException::class);
        // given
        $product = 'cola';
        $received = new ReceiveCash([100 => 2]);
        $stocks = new ProductStocks();
        $stocks->add(ProductStock::create('cola', 150, 1));
        $inventory = new CoinInventory([500 => 1]);

        // when
        $service = new PurchaseService();
        $service->purchase($product, $received, $stocks, $inventory);
    }

    /**
     * @throws OutOfStockException
     * @throws NotFoundProductException
     * @throws MoneyShortageException
     */
    public function testPurchase_InsufficientPaidAmountException()
    {
        // 投入金額の不足
        $this->expectException(InsufficientPaidAmountException::class);
        // given
        $product = 'cola';
        $received = new ReceiveCash([100 => 1]);
        $stocks = new ProductStocks();
        $stocks->add(ProductStock::create('cola', 150, 1));
        $inventory = new CoinInventory([500 => 1]);

        // when
        $service = new PurchaseService();
        $service->purchase($product, $received, $stocks, $inventory);
    }

    /**
     * @throws NotFoundProductException
     * @throws MoneyShortageException
     * @throws InsufficientPaidAmountException
     */
    public function testPurchase_OutOfStockException()
    {
        // 在庫切れ
        $this->expectException(OutOfStockException::class);
        // given
        $product = 'cola';
        $received = new ReceiveCash([100 => 2]);
        $stocks = new ProductStocks();
        $stocks->add(ProductStock::create('cola', 150, 0));
        $inventory = new CoinInventory([50 => 1]);

        // when
        $service = new PurchaseService();
        $service->purchase($product, $received, $stocks, $inventory);
    }
}
