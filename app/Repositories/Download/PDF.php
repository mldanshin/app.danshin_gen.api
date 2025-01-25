<?php

namespace App\Repositories\Download;

use App\Helpers\Date as DateHelper;
use App\Helpers\Person as PersonHelper;
use App\Models\Download\People\Person as PersonModel;
use App\Models\Download\People\PersonShort as PersonShortModel;
use App\Models\Download\People\Photo as PhotoModel;
use App\Services\Photo\FileSystem as PhotoFileSystem;
use FPDF\FPDF;
use Illuminate\Support\Collection;

final class PDF
{
    private FPDF $pdf;

    private int $heightCell;

    private int $leftMarginPage;

    public function __construct(public readonly PhotoFileSystem $photoFileSystem) {}

    public function createFile(callable $func, string $path): void
    {
        $this->pdf = new FPDF;
        $this->pdf->AddFont('arial', '', 'arial.php');
        $this->pdf->AddFont('arial_bold', '', 'arial_bold.php');
        $this->heightCell = 5;
        $this->setFontDefault();
        $this->pdf->AddPage();
        $this->leftMarginPage = $this->pdf->GetX();

        $func();

        $this->pdf->Output($path, 'F');
    }

    public function createCard(PersonModel $person): void
    {
        $fullName = PersonHelper::surname($person->surname).' '
            .PersonHelper::name($person->name).' '
            .PersonHelper::patronymic($person->patronymic);
        $this->createCellStrong($fullName);

        if ($person->oldSurname !== null) {
            $this->createCell(
                __('person.old_surname.label'),
                PersonHelper::oldSurname($person->oldSurname)
            );
        }

        $this->createCell(
            __('person.gender.label'),
            $person->gender
        );

        $this->createCell(
            __('person.birth_date.label'),
            $person->birthDate?->string
        );

        $this->createCell(
            __('person.birth_place.label'),
            $person->birthPlace
        );

        if (! $person->isLive) {
            $this->createCell(
                __('person.death_date.label'),
                $person->deathDate?->string
            );

            $this->createCell(
                __('person.burial_place.label'),
                $person->burialPlace
            );
        }

        if ($person->note !== null) {
            $this->createCell(
                __('person.note.label'),
                $person->note
            );
        }

        if ($person->activities !== null && $person->activities->isNotEmpty()) {
            $this->createCell(
                __('person.activities.label'),
                $person->activities->implode(',')
            );
        }

        if ($person->emails !== null && $person->emails->isNotEmpty()) {
            $this->createCell(
                __('person.emails.label'),
                $person->emails->implode(',')
            );
        }

        if ($person->internet !== null && $person->internet->isNotEmpty()) {
            $this->pdf->Cell(39, $this->heightCell, __('person.internet.label').':');
            $person->internet->each(
                function ($item) {
                    $this->pdf->SetTextColor(0, 0, 255);
                    $this->pdf->Write($this->heightCell, $item->name, $item->url);
                    $this->pdf->Write($this->heightCell, ', ');
                    $this->pdf->SetTextColor(0, 0, 0);
                }
            );
            $this->pdf->Ln();
        }

        if ($person->phones !== null && $person->phones->isNotEmpty()) {
            $this->createCell(
                __('person.phones.label'),
                $person->phones->implode(',')
            );
        }

        if ($person->residences !== null) {
            $collection = $person->residences->map(
                function ($item) {
                    $str = $item->name;
                    if ($item->date !== null && ! $item->date->isEmpty) {
                        $str .= __(
                            'person.residences.date.content',
                            ['date' => DateHelper::format($item->date->string)]
                        );
                    }

                    return $str;
                }
            );
            $this->createCell(
                __('person.residences.label'),
                $collection->implode(',')
            );
        }

        if ($person->parents !== null) {
            $i = 0;
            $person->parents->each(
                function ($item) use (&$i) {
                    if ($i === 0) {
                        $this->pdf->Cell(22, $this->heightCell, __('person.parents.label').':');
                    } else {
                        $this->pdf->Cell(22, $this->heightCell);
                    }
                    $i++;
                    $this->pdf->Write($this->heightCell, $this->getPersonShort($item->person));
                    $this->pdf->Ln();
                }
            );
        }

        if ($person->marriages !== null) {
            $i = 0;
            $person->marriages->each(
                function ($item) use (&$i) {
                    if ($i === 0) {
                        $this->pdf->Cell(43, $this->heightCell, __('person.marriages.label').':');
                    } else {
                        $this->pdf->Cell(43, $this->heightCell);
                    }
                    $i++;
                    $this->pdf->Write($this->heightCell, $this->getPersonShort($item->soulmate));
                    $this->pdf->Ln();
                }
            );
        }

        if ($person->children !== null) {
            $i = 0;
            $person->children->each(
                function ($item) use (&$i) {
                    if ($i === 0) {
                        $this->pdf->Cell(12, $this->heightCell, __('person.children.label').':');
                    } else {
                        $this->pdf->Cell(12, $this->heightCell);
                    }
                    $i++;
                    $this->pdf->Write(5, $this->getPersonShort($item));
                    $this->pdf->Ln();
                }
            );
        }

        if ($person->photo !== null) {
            $this->insertImages($person->photo);
        }

        $this->pdf->Ln();
        $this->pdf->Ln();
    }

    private function createCellStrong(string $value): void
    {
        $this->pdf->SetFont('arial_bold', '', 14);
        $this->pdf->Cell(20, $this->heightCell, $value);
        $this->pdf->Ln();
        $this->setFontDefault();
    }

    private function createCell(string $label, ?string $value): void
    {
        $this->pdf->MultiCell(
            360,
            $this->heightCell,
            $label.': '.
            $value ?? '',
            align: 'L'
        );
    }

    private function setFontDefault(): void
    {
        $this->pdf->SetFont('arial', '', 12);
    }

    /**
     * @param  Collection<int, PhotoModel>  $photo
     */
    private function insertImages(Collection $photo): void
    {
        $x = 0;
        $y = 0;
        $widthPhoto = 30;
        $maxHeightPhoto = 40;
        $marginBottomPhoto = 5;

        $photo->each(
            function ($item) use (&$x, &$y, $widthPhoto, $maxHeightPhoto, $marginBottomPhoto) {
                if ($x === 0) {
                    $x = $this->pdf->GetX();
                    $y = $this->pdf->GetY();
                } else {
                    $x += $this->pdf->GetX() + $widthPhoto;
                    if (($x + $widthPhoto) > $this->pdf->GetPageWidth()) {
                        $x = $this->leftMarginPage;
                        $y = $this->pdf->GetY() + $maxHeightPhoto + $marginBottomPhoto;
                    } else {
                        $y = $this->pdf->GetY();
                    }
                }

                if ($this->pdf->GetPageHeight() < ($y + $maxHeightPhoto)) {
                    $this->pdf->AddPage();
                    $x = $this->pdf->GetX();
                    $y = $this->pdf->GetY();
                }

                $this->pdf->Image(
                    $this->convertWebPToJpg($item->path),
                    x: $x,
                    y: $y,
                    w: $widthPhoto
                );
            }
        );
        $this->pdf->SetXY(0, $y + 40);
    }

    private function getPersonShort(PersonShortModel $person): string
    {
        return PersonHelper::surname($person->surname).' '
            .PersonHelper::name($person->name).' '
            .PersonHelper::patronymic($person->patronymic);
    }

    private function convertWebPToJpg(string $pathWebp): string
    {
        $imagick = new \Imagick($pathWebp);
        $imagick->setImageFormat('JPG');
        $imagick->setImageCompressionQuality(80);

        $pathJpeg = $this->photoFileSystem->getPathTemp(uniqid().'.jpg');
        $imagick->writeImage($pathJpeg);

        $imagick->clear();
        $imagick->destroy();

        return $pathJpeg;
    }
}
