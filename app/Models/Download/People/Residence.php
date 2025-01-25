<?php

namespace App\Models\Download\People;

use App\Models\Date;

final readonly class Residence
{
    public function __construct(
        public string $name,
        public ?Date $date
    ) {}
}
