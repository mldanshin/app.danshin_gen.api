<?php

namespace App\View\Tree;

final readonly class PointXY
{
    public function __construct(
        public int $x,
        public int $y
    ) {}
}
