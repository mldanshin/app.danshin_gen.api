<?php

namespace App\Backup\Photo;

use App\Models\Backup\PhotoFile;
use App\Services\Photo\FileSystem as PhotoFileSystem;

final class Storage
{
    public readonly string $directory;

    public readonly ?string $path;

    private PhotoFileSystem $photoFileSystem;

    /**
     * @var array<PhotoFile>
     */
    private array $files;

    public function __construct()
    {
        $this->directory = storage_path('app').'/backup/';

        $this->initializePhotoFileSystem();
        $this->initializeFiles();
        $this->initializePath();
        $this->createFile();
    }

    public function createFile(): void
    {
        if ($this->path === null) {
            return;
        }

        $zip = new \ZipArchive;
        if (file_exists($this->path)) {
            $zip->open($this->path, \ZipArchive::OVERWRITE);
        } else {
            $zip->open($this->path, \ZipArchive::CREATE);
        }

        foreach ($this->files as $file) {
            $zip->addFile($file->path, $file->entryName);
        }

        $zip->close();
    }

    private function initializePhotoFileSystem(): void
    {
        $this->photoFileSystem = PhotoFileSystem::instance();
    }

    private function initializeFiles(): void
    {
        $this->files = $this->photoFileSystem->getFilesArchive();
    }

    private function initializePath(): void
    {
        if (count($this->files) > 0) {
            $this->path = $this->directory.'photo.zip';
        } else {
            $this->path = null;
        }
    }
}
