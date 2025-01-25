<?php

namespace App\View\Tree;

final class Image extends Element
{
    public function __construct(
        int $width,
        int $height,
        public readonly string $href
    ) {
        parent::__construct(new Size($width, $height));
    }

    public function setPoint(int $x, int $y): void
    {
        $this->point = new PointXY($x, $y);
    }
}
