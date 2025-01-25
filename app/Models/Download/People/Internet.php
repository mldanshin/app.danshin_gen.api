<?php

namespace App\Models\Download\People;

final readonly class Internet
{
    public function __construct(
        public string $url,
        public string $name
    ) {}
}
