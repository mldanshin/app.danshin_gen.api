<?php

namespace App\Models\Download\People;

use Illuminate\Support\Collection;

final readonly class PersonShort
{
    /**
     * @param  Collection<int, string>|null  $oldSurname
     */
    public function __construct(
        public ?string $surname,
        public ?Collection $oldSurname,
        public ?string $name,
        public ?string $patronymic
    ) {}
}
