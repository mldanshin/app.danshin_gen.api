<?php

namespace App\View\Tree;

use App\Models\Tree\Family as FamilyModel;
use App\Models\Tree\Interactive as InteractiveModel;
use App\Models\Tree\Person as PersonModel;
use Illuminate\Support\Collection;

final class Family extends Element
{
    public readonly Person $person;

    /**
     * @var Collection <int, Person>
     */
    public readonly Collection $marriage;

    /**
     * @var Collection<int, Family>
     */
    public readonly Collection $children;

    private ?ParentChildrenRelation $parentRelation = null;

    private int $margine;

    public function __construct(
        FamilyModel $family,
        private StylePerson $style,
        private ?InteractiveModel $interactive,
    ) {
        $this->margine = $this->style->margine;

        $this->person = new Person($family->person, $this->style, $this->interactive);
        $this->initializeMarriage($family->marriage);
        $this->initializeChildren($family->children);

        parent::__construct($this->getSize());
    }

    public function getParentRelation(): ?ParentChildrenRelation
    {
        return $this->parentRelation;
    }

    public function setPointWrapper(int $x, int $y, PointXY $parent): void
    {
        $this->setPoint($x, $y);
        $this->parentRelation = new ParentChildrenRelation(
            $parent,
            new PointXY(
                $this->person->getPoint()->x + ($this->person->size->width / 2),
                $this->person->getPoint()->y
            )
        );
    }

    public function setPoint(int $x, int $y): void
    {
        $this->point = new PointXY($x, $y);

        $middleWidthFamily = $x + ($this->size->width / 2);

        $y = $this->setPointPerson($middleWidthFamily, $y);
        $y = $this->setPointMarriage($middleWidthFamily, $y);
        $this->setPointChildren($x, $y);
    }

    /**
     * @param  Collection|PersonModel[]  $people
     */
    private function initializeMarriage(Collection $people): void
    {
        $this->marriage = collect();
        foreach ($people as $person) {
            $this->marriage->add(new Person($person, $this->style, $this->interactive));
        }
    }

    /**
     * @param  Collection<int, FamilyModel>  $children
     */
    private function initializeChildren(Collection $children): void
    {
        $this->children = collect();

        foreach ($children as $item) {
            $this->children->add(new Family($item, $this->style, $this->interactive));
        }
    }

    private function getSize(): Size
    {
        $marriage = $this->getSizeMarriage();
        $childrens = $this->getSizeChildrens($this->children);

        $widthMax = max(
            $this->person->size->width + ($this->margine * 2),
            $marriage->width,
            $childrens->width,
        );

        return new Size(
            $widthMax,
            ($this->person->size->height + ($this->margine * 2))
                + $marriage->height
                + $childrens->height
        );
    }

    private function getSizeMarriage(): Size
    {
        if ($this->marriage->isEmpty()) {
            return new Size(0, 0);
        }

        $widthMaxElement = null;
        $height = 0;
        $width = 0;

        foreach ($this->marriage as $marriage) {
            $widthMaxElement = $this->compareWidthElement($widthMaxElement, $marriage);
            $height += $marriage->size->height + ($this->margine * 2);
        }

        if ($widthMaxElement !== null) {
            $width = $widthMaxElement->size->width;
        }

        return new Size($width + ($this->margine * 2), $height);
    }

    /**
     * @param  Collection|Family[]  $families
     */
    private function getSizeChildrens(Collection $families): Size
    {
        if ($families->isEmpty()) {
            return new Size(0, 0);
        }

        $width = 0;
        $height = 0;

        $heightMaxElement = null;
        foreach ($families as $family) {
            $heightMaxElement = $this->compareHeightElement($heightMaxElement, $family);
            $width = $width + $family->size->width + ($this->margine * 2);
        }
        if ($heightMaxElement !== null) {
            $height = $heightMaxElement->size->height;
        }

        return new Size($width, $height + ($this->margine * 2));
    }

    private function setPointPerson(int $middleWidthFamily, int $y): int
    {
        $halfPerson = $this->person->size->width / 2;
        $this->person->setPoint(
            $middleWidthFamily - $halfPerson,
            $y + $this->margine
        );

        return $y + $this->person->size->height + $this->margine;
    }

    private function setPointMarriage(int $middleWidthFamily, int $y): int
    {
        foreach ($this->marriage as $marriage) {
            $halfPerson = $marriage->size->width / 2;
            $marriage->setPoint(
                $middleWidthFamily - $halfPerson,
                $y + $this->margine
            );
            $y += $marriage->size->height + $this->margine;
        }

        return $y;
    }

    private function setPointChildren(int $x, int $y): void
    {
        $parentWidth = $this->size->width;
        $childrensWidth = 0;
        foreach ($this->children as $item) {
            $childrensWidth += $item->size->width;
        }
        $span = ($parentWidth - $childrensWidth) / ($this->children->count() + 1);

        foreach ($this->children as $item) {
            $item->setPointWrapper(
                $x + $span,
                $y,
                new PointXY(
                    $this->person->getPoint()->x + ($this->person->size->width / 2),
                    $this->person->getPoint()->y + $this->person->size->height
                )
            );
            $x += $item->size->width;
        }
    }
}
