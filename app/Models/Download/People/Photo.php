<?php

namespace App\Models\Download\People;

use App\Models\Date;

final readonly class Photo
{
    public function __construct(
        public string $url,
        public string $path,
        public ?Date $date
    ) {}
}
