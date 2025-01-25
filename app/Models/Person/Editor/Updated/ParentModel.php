<?php

namespace App\Models\Person\Editor\Updated;

final readonly class ParentModel
{
    public function __construct(
        public int $person,
        public int $role
    ) {}
}
