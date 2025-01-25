<?php

namespace App\Models\Person\Editor\Stored;

final readonly class Internet
{
    public function __construct(
        public string $url,
        public string $name
    ) {}
}
