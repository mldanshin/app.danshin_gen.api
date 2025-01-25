<?php

namespace App\Models\Person\Editor\Stored;

final readonly class ParentModel
{
    public function __construct(
        public int $person,
        public int $role
    ) {}
}
