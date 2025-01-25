<?php

namespace App\View\Tree;

final readonly class StylePerson
{
    public function __construct(
        public int $margine,
        public float $strokeWidth,
        public int $padding,
        public int $fontSize,
        public int $lineSpacing,
        public Size $button
    ) {}
}
