<?php

namespace App\Support;

use App\Repositories\Download\FileSystem;
use Illuminate\Support\Facades\File;

final class DownloadRepository
{
    public readonly string $pathDirectory;

    public function __construct()
    {
        $this->pathDirectory = FileSystem::instance()->pathDirectory;
    }

    public function clear(): bool
    {
        return File::cleanDirectory($this->pathDirectory);
    }
}
