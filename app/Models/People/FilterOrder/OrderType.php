<?php

namespace App\Models\People\FilterOrder;

enum OrderType: string
{
    case AGE = 'age';
    case NAME = 'name';
}
