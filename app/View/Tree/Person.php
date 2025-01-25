<?php

namespace App\View\Tree;

use App\Helpers\Date as DateHelper;
use App\Helpers\Person as PersonHelper;
use App\Models\Tree\Interactive as InteractiveModel;
use App\Models\Tree\Person as PersonModel;

final class Person extends Element
{
    public readonly string $id;

    public readonly Text $surname;

    public readonly ?Text $oldSurname;

    public readonly Text $name;

    public readonly Text $patronymic;

    public readonly Text $periodLive;

    public readonly bool $isPersonTarget;

    public readonly ?Link $linkCard;

    public readonly ?Link $linkTree;

    private int $fontSize;

    private int $lineSpacing;

    private int $padding;

    private ?Size $linkSize;

    private int $lineHeight;

    public function __construct(PersonModel $model, StylePerson $style, ?InteractiveModel $interactive)
    {
        $this->fontSize = $style->fontSize;
        $this->lineSpacing = $style->lineSpacing;
        $this->padding = $style->padding;
        $this->linkSize = ($interactive !== null) ? $style->button : null;
        $this->lineHeight = $this->fontSize + $this->lineSpacing;
        $this->id = (string) $model->id;
        $this->isPersonTarget = $model->isPersonTarget;

        $surname = PersonHelper::surname($model->surname);
        $oldSurname = ($model->oldSurname === null) ? null : PersonHelper::oldSurname($model->oldSurname);
        $name = PersonHelper::name($model->name);
        $patronymic = PersonHelper::patronymic($model->patronymic);
        $periodLive = DateHelper::periodLive($model->birthDate?->string, $model->deathDate?->string);

        $max = 0;
        $max = $this->getLongLengthString($surname, 0);
        $max = $this->getLongLengthString($oldSurname, $max);
        $max = $this->getLongLengthString($name, $max);
        $max = $this->getLongLengthString($patronymic, $max);
        $max = $this->getLongLengthString($periodLive, $max);

        $width = $max * $this->fontSize / 1.5;
        if ($this->linkSize !== null) {
            $widthLinks = ($this->linkSize->width * 2) + ($this->fontSize * 2);
            if ($widthLinks > $width) {
                $width = $widthLinks;
            }
        }

        $height = ($this->lineHeight * 4)
            + ($this->padding * 2)
            + (($this->linkSize === null) ? 0 : $this->linkSize->height)
            + $this->lineSpacing;

        parent::__construct(
            new Size(
                $width + ($this->padding * 2),
                ($oldSurname === null) ? $height : ($height + $this->lineHeight)
            )
        );

        $this->surname = new Text($surname);
        $this->oldSurname = ($oldSurname === null) ? null : new Text($oldSurname);
        $this->name = new Text($name);
        $this->patronymic = new Text($patronymic);
        $this->periodLive = new Text($periodLive);

        $this->initializeLinks($interactive);
    }

    public function setPoint(int $x, int $y): void
    {
        $this->point = new PointXY($x, $y);

        $x = $x + ($this->size->width / 2);
        $y = $y + $this->padding + $this->fontSize;

        $this->surname->setPoint($x, $y);
        $this->oldSurname?->setPoint($x, $y += $this->lineHeight);
        $this->name->setPoint($x, $y += $this->lineHeight);
        $this->patronymic->setPoint($x, $y += $this->lineHeight);
        $this->periodLive->setPoint($x, $y += $this->lineHeight);

        $y += $this->lineSpacing;

        if ($this->linkCard !== null) {
            $this->linkCard->setPoint(
                $x - $this->linkSize->width - $this->fontSize,
                $y
            );
        }

        if ($this->linkTree) {
            $this->linkTree->setPoint(
                $x + $this->fontSize,
                $y
            );
        }
    }

    private function getLongLengthString(?string $string, int $maxSize): int
    {
        if ($string === null) {
            return $maxSize;
        }

        $length = strlen($string);
        if ($length > $maxSize) {
            return $length;
        } else {
            return $maxSize;
        }
    }

    private function initializeLinks(?InteractiveModel $interactive): void
    {
        if ($interactive !== null) {
            $this->linkCard = new Link(
                $this->id,
                $interactive->pathPerson.'/'.$this->id,
                $interactive->imagePerson,
                $this->linkSize
            );

            $this->linkTree = new Link(
                $this->id,
                $interactive->pathTree.'/'.$this->id,
                $interactive->imageTree,
                $this->linkSize
            );
        } else {
            $this->linkCard = null;
            $this->linkTree = null;
        }
    }
}
