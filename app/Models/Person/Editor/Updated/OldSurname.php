<?php

namespace App\Models\Person\Editor\Updated;

final readonly class OldSurname
{
    public function __construct(
        public string $surname,
        public int $order
    ) {}
}
