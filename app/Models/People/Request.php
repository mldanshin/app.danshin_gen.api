<?php

namespace App\Models\People;

use App\Models\People\FilterOrder\OrderType;

final readonly class Request
{
    public ?OrderType $orderType;

    public function __construct(
        ?string $order,
        public ?string $search
    ) {
        $this->orderType = OrderType::tryFrom($order);
    }
}
