<?php

namespace App\Repositories\Download;

use Illuminate\Contracts\Filesystem\Filesystem as FilesystemIlluminate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileSystem
{
    protected const PATH_RELATIVE = 'download/';

    public readonly string $pathDirectory;

    public function __construct(
        public readonly FilesystemIlluminate $disk
    ) {
        $this->pathDirectory = $disk->path(self::PATH_RELATIVE);
        $this->createPathDirectory();
    }

    public static function instance(): static
    {
        return new static(Storage::disk('public'));
    }

    private function createPathDirectory(): void
    {
        if (! File::exists($this->pathDirectory)) {
            File::makeDirectory($this->pathDirectory);
            File::chmod($this->pathDirectory, 0777);
        }
    }
}
