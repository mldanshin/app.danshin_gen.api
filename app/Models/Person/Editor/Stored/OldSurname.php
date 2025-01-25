<?php

namespace App\Models\Person\Editor\Stored;

final readonly class OldSurname
{
    public function __construct(
        public string $surname,
        public int $order
    ) {}
}
