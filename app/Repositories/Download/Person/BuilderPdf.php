<?php

namespace App\Repositories\Download\Person;

use App\Models\Download\People\Person as PersonModel;
use App\Repositories\Download\PDF;
use App\Services\Photo\FileSystem as PhotoFileSystem;

final class BuilderPdf extends BuilderAbstract
{
    public function __construct(public readonly PhotoFileSystem $photoFileSystem) {}

    public function create(string $pathDirectory, PersonModel $person): string
    {
        $pdf = new PDF($this->photoFileSystem);

        $func = fn () => $pdf->createCard($person);

        $path = $pathDirectory."danshin_genealogy_person_{$person->id}.pdf";

        $pdf->createFile(
            $func,
            $path
        );

        return $path;
    }
}
