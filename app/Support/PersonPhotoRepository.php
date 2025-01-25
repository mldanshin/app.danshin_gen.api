<?php

namespace App\Support;

use App\Services\Photo\FileSystem as PhotoFileSystem;
use Illuminate\Support\Facades\File;

final class PersonPhotoRepository
{
    private string $path;

    public function __construct()
    {
        $this->path = PhotoFileSystem::instance()->getPathDirectoryTemp();
    }

    public function clearTempDir(): bool
    {
        $files = File::allFiles($this->path);
        $timeCurrent = time();
        $fileStorageTime = config('app.storage.photo.time_files_temp');

        foreach ($files as $file) {
            $time = File::lastModified($file);
            if (($timeCurrent - $time) > $fileStorageTime) {
                $res = File::delete($file);
                if ($res === false) {
                    return false;
                }
            }
        }

        return true;
    }
}
