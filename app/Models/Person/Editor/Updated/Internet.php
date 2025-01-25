<?php

namespace App\Models\Person\Editor\Updated;

final readonly class Internet
{
    public function __construct(
        public string $url,
        public string $name
    ) {}
}
