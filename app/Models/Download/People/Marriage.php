<?php

namespace App\Models\Download\People;

final readonly class Marriage
{
    public function __construct(
        public PersonShort $soulmate,
        public string $role
    ) {}
}
