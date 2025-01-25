<?php

namespace App\Http\Requests\Person;

trait Phone
{
    public function cleanPhone(string $dirty): string
    {
        $dirty = str_replace(['-', ' '], '', $dirty);

        return substr($dirty, -10, 10);
    }
}
