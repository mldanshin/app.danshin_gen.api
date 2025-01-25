<?php

namespace App\Repositories\Download\Person;

use App\Exceptions\DataNotFoundException;
use App\Repositories\Download\CreatorFile as CreatorFileBase;
use App\Repositories\Download\People as PeopleRepository;
use App\Services\Photo\FileSystem as PhotoFileSystem;

final class CreatorFile extends CreatorFileBase
{
    private PeopleRepository $peopleRepository;

    private readonly FileType $fileType;

    private PhotoFileSystem $photoFileSystem;

    public function __construct(
        string $fileType,
        private readonly int $personId
    ) {
        $this->fileType = $this->fileTypeFrom($fileType);

        $this->photoFileSystem = PhotoFileSystem::instance();

        $this->peopleRepository = new PeopleRepository($this->photoFileSystem);
    }

    /**
     * @throws DataNotFoundException
     */
    public function create(string $pathDirectory): string
    {
        $person = $this->peopleRepository->getById($this->personId);

        if ($person === null) {
            throw new DataNotFoundException("The requested person {$this->personId} does not exist.");
        }

        return $this->createBuilder()->create($pathDirectory, $person);
    }

    private function createBuilder(): BuilderAbstract
    {
        switch ($this->fileType) {
            case $this->fileType::PDF:
                return new BuilderPdf($this->photoFileSystem);
        }
    }

    /**
     * @throws DataNotFoundException
     */
    private function fileTypeFrom(string $fileType): FileType
    {
        $fileType = FileType::tryFrom($fileType);
        if ($fileType === null) {
            throw new DataNotFoundException('Transferred file format is not supported.');
        }

        return $fileType;
    }
}
