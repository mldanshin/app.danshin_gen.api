<?php

namespace App\Models\Dates;

use App\Models\Date;

abstract readonly class Event
{
    public function __construct(
        public Date $date,
        public Person $person
    ) {}
}
