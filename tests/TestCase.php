<?php

namespace Tests;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function getHeaderAdminToken(): array
    {
        return ['Authorization' => 'Bearer 4pawb2A0kxKSQuUAMtbCjH6n3CBbAj8snUnFU0Zs'];
    }

    protected function getHeaderUserToken(): array
    {
        return ['Authorization' => 'Bearer GQNzZM0tHNTPtaxEv56832MnCAXtmHzt7kNPtgh8'];
    }

    protected function getPathImage(): string
    {
        return storage_path('framework/public_fake/test.png');
    }

    protected function seedStorage(Filesystem $disk): void
    {
        File::copyDirectory(
            storage_path('framework/public_fake'),
            $disk->path('')
        );
    }

    protected function clearStorage(Filesystem $disk): void
    {
        File::cleanDirectory($disk->path(''));
    }

    protected function setConfigFakeDisk(): void
    {
        config(['filesystems.disks.local.root' => storage_path('framework/testing/disks/public')]);
    }
}
