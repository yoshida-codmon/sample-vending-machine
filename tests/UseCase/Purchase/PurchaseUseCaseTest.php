<?php

use Domain\Model\ChangeCash;
use Domain\Model\CoinInventory;
use Domain\Model\ProductStock\ProductId;
use Domain\Model\ProductStock\ProductStock;
use Domain\Model\ProductStock\ProductStocks;
use Domain\Model\ReceiveCash;
use Domain\Repository\CoinInventoryRepository;
use Domain\Repository\ProductStockRepository;
use Domain\Service\PurchaseService;
use Domain\Value\Error\InsufficientPaidAmountException;
use Domain\Value\Error\MoneyShortageException;
use Domain\Value\Error\NotFoundProductException;
use Domain\Value\Error\OutOfStockException;
use Infrastructure\Repository\InMemoryCoinInventoryRepository;
use Infrastructure\Repository\InMemoryProductStockRepository;
use PHPUnit\Framework\TestCase;
use UseCase\Purchase\PurchaseInput;
use UseCase\Purchase\PurchaseUseCase;

class PurchaseUseCaseTest extends TestCase
{
    private readonly ProductStockRepository $stockRepository;
    private readonly CoinInventoryRepository $inventoryRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $stocks = new ProductStocks();
        $stocks->add(ProductStock::create('cola', 150, 1));
        $this->stockRepository->save($stocks);

    }

    public function __construct()
    {
        parent::__construct();
        $this->stockRepository = new InMemoryProductStockRepository();
        $this->inventoryRepository = new InMemoryCoinInventoryRepository();
    }

    /**
     * @throws NotFoundProductException
     * @throws MoneyShortageException
     * @throws InsufficientPaidAmountException
     * @throws OutOfStockException
     */
    public function testHandle(): void
    {
        // 500円で150円のものを購入 -> 350円のおつり

        // given
        $inventory = new CoinInventory([100 => 3, 50 => 1]);
        $this->inventoryRepository->save($inventory);

        $service = new PurchaseService();
        $useCase = new PurchaseUseCase(
            $this->stockRepository,
            $this->inventoryRepository,
            $service
        );

        // when
        $input = new PurchaseInput(
            'cola',
            new ReceiveCash([500 => 1])
        );
        $actual = $useCase->handle($input);

        // then
        $expected = new ChangeCash([100 => 3, 50 => 1]);
        $this->assertEquals($expected, $actual);

        // * リポジトリ内の更新を確認
        $stocks = $this->stockRepository->load();
        $stock = $stocks->get(ProductId::from('cola'));
        $this->assertEquals(0, $stock->getQuantity());

        $this->assertEquals(
            new CoinInventory([500 => 1]), // 投入金額のみ
            $this->inventoryRepository->load()
        );
    }
}