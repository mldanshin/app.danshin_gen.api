<?php

namespace Tests\Feature\Support;

use App\Support\DownloadRepository;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

final class DownloadRepositoryTest extends TestCase
{
    public function test_clear(): void
    {
        $this->setConfigFakeDisk();

        $obj = new DownloadRepository;

        $directory = $obj->pathDirectory;
        $this->prepareFiles($directory);

        // testing
        $obj->clear();

        // verify
        $this->assertCount(0, File::allFiles($directory));
    }

    /**
     * @return string[]
     */
    private function prepareFiles(string $directory): array
    {
        $files = [
            $directory.'fresh1.txt',
            $directory.'fresh2.txt',
            $directory.'fresh3.txt',
            $directory.'fresh4.txt',
        ];

        foreach ($files as $file) {
            File::put($file, 'Bla Bla');
        }

        return $files;
    }
}
