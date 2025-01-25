<?php

namespace App\Models\Dates;

enum DateType: int
{
    case BIRTH = 1;
    case DEATH = 2;

    public function getStringValue(): string
    {
        return match($this) {
            self::BIRTH => 'BIRTH',
            self::DEATH => 'DEATH',
        };
    }
}
