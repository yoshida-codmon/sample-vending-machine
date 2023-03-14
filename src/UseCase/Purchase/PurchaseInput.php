<?php

namespace UseCase\Purchase;

use Domain\Model\ReceiveCash;

class PurchaseInput
{
    public function __construct(
        public readonly string      $product,
        public readonly ReceiveCash $receiveCash,
    )
    {
    }
}