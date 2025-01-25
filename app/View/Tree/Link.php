<?php

namespace App\View\Tree;

final class Link
{
    public function __construct(
        public readonly string $personId,
        public readonly string $path,
        public readonly string $imagePath,
        public readonly Size $size,
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
