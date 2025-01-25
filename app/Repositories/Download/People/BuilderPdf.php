<?php

namespace App\Repositories\Download\People;

use App\Models\Download\People\Person as PersonModel;
use App\Repositories\Download\PDF;
use App\Services\Photo\FileSystem as PhotoFileSystem;
use Illuminate\Support\Collection;

final class BuilderPdf extends BuilderAbstract
{
    private const FILE_NAME = 'danshin_genealogy_people.pdf';

    public function __construct(public readonly PhotoFileSystem $photoFileSystem) {}

    /**
     * returns the path to the created file
     *
     * @param  Collection<int, PersonModel>  $people
     */
    public function create(string $pathDirectory, Collection $people): string
    {
        $pdf = new PDF($this->photoFileSystem);

        $func = function () use ($pdf, $people) {
            foreach ($people as $person) {
                $pdf->createCard($person);
            }
        };

        $path = $pathDirectory.self::FILE_NAME;

        $pdf->createFile(
            $func,
            $path
        );

        return $path;
    }
}
