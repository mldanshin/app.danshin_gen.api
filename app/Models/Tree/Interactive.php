<?php

namespace App\Models\Tree;

final readonly class Interactive
{
    public function __construct(
        public string $pathPerson,
        public string $pathTree,
        public string $imagePerson,
        public string $imageTree
    ) {}
}
