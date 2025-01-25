<?php

namespace App\View\Tree;

final readonly class Size
{
    public function __construct(
        public int $width,
        public int $height
    ) {}
}
