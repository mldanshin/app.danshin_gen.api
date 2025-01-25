<?php

namespace App\Repositories\Download\Photo;

use App\Exceptions\NoContentException;
use App\Models\Download\Photo\FileArchive as File;
use App\Repositories\Download\CreatorFile as CreatorFileBase;
use App\Services\Photo\FileSystem as PhotoFileSystem;

final class CreatorFile extends CreatorFileBase
{
    private const FILE_NAME = 'danshin_genealogy_photo.zip';

    private PhotoFileSystem $photoFileSystem;

    /**
     * @var File[]
     */
    private array $files;

    public function __construct()
    {
        $this->photoFileSystem = PhotoFileSystem::instance();
        $this->files = $this->photoFileSystem->getFilesArchive();
    }

    /**
     * @throws NoContentException
     */
    public function create(string $pathDirectory): string
    {
        if (count($this->files) > 0) {
            $pathFile = $pathDirectory.self::FILE_NAME;
            $this->createFile($pathFile);

            return $pathFile;
        } else {
            return throw new NoContentException('There are no photos to download');
        }
    }

    private function createFile($pathFile): void
    {
        $zip = new \ZipArchive;
        if (file_exists($pathFile)) {
            $zip->open($pathFile, \ZipArchive::OVERWRITE);
        } else {
            $zip->open($pathFile, \ZipArchive::CREATE);
        }

        foreach ($this->files as $file) {
            $zip->addFile($file->path, $file->entryName);
        }

        $zip->close();
    }
}
