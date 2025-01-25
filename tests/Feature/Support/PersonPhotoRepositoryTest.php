<?php

namespace Tests\Feature\Support;

use App\Services\Photo\FileSystem as PhotoFileSystem;
use App\Support\PersonPhotoRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class PersonPhotoRepositoryTest extends TestCase
{
    public function test_clear_tmp_dir(): void
    {
        $countFresh = 4;
        $countOld = 5;

        $this->setConfigFakeDisk();

        // prepare
        $disk = Storage::fake('public');
        $fileSystem = new PhotoFileSystem($disk);

        $this->prepareFreshFiles($countFresh, $fileSystem);
        $this->prepareOldFiles($countOld, $fileSystem);

        // testing
        $obj = new PersonPhotoRepository;
        $obj->clearTempDir();

        // verify
        $files = File::files($fileSystem->getPathDirectoryTemp());
        $this->assertTrue(count($files) === $countFresh);
    }

    private function prepareFreshFiles(int $count, PhotoFileSystem $fileSystem): void
    {
        for ($i = 0; $i < $count; $i++) {
            $fileTmp = $fileSystem->getPathTemp("fresh{$i}.png");
            File::copy($this->getPathImage(), $fileTmp);
        }
    }

    private function prepareOldFiles(int $count, PhotoFileSystem $fileSystem): void
    {
        $timeCurrent = time();
        $timefileStorage = config('app.storage.photo.time_files_temp');

        for ($i = 0; $i < $count; $i++) {
            $fileTmp = $fileSystem->getPathTemp("old{$i}.png");
            File::copy($this->getPathImage(), $fileTmp);
            touch(
                $fileTmp,
                $timeCurrent - ($timefileStorage + 10)
            );
        }
    }
}
