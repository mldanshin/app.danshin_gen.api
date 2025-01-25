<?php

namespace App\View\Tree;

final readonly class ParentChildrenRelation
{
    public function __construct(
        public PointXY $point1,
        public PointXY $point2
    ) {}
}
