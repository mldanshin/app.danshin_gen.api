<?php

namespace App\View\Tree;

final class Text
{
    public function __construct(
        public readonly string $content,
        private ?PointXY $point = null,
    ) {}

    public function getPoint(): ?PointXY
    {
        return $this->point;
    }

    public function setPoint(int $x, int $y): void
    {
        $this->point = new PointXY($x, $y);
    }
}
