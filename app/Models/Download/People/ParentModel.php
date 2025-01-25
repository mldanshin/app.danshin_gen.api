<?php

namespace App\Models\Download\People;

final readonly class ParentModel
{
    public function __construct(
        public PersonShort $person,
        public string $role,
    ) {}
}
