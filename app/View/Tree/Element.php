<?php

namespace App\View\Tree;

abstract class Element
{
    protected PointXY $point;

    public function __construct(public readonly Size $size) {}

    public function getPoint(): PointXY
    {
        return $this->point;
    }

    abstract public function setPoint(int $x, int $y): void;

    protected function compareWidthElement(?Element $element1, Element $element2): Element
    {
        if ($element1 === null) {
            return $element2;
        }

        if ($element1->size->width > $element2->size->width) {
            return $element1;
        } else {
            return $element2;
        }
    }

    protected function compareHeightElement(?Element $element1, Element $element2): Element
    {
        if ($element1 === null) {
            return $element2;
        }

        if ($element1->size->height > $element2->size->height) {
            return $element1;
        } else {
            return $element2;
        }
    }
}
