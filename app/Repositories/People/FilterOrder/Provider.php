<?php

namespace App\Repositories\People\FilterOrder;

use App\Models\People\FilterOrder\OrderType;

final class Provider
{
    public function get(?OrderType $orderType): FilteringOrderingContract
    {
        switch ($orderType) {
            case OrderType::AGE:
                return new Age;
            case OrderType::NAME:
                return new Name;
            default:
                return new Name;
        }
    }
}
