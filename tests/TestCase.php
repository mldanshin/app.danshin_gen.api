<?php

namespace Tests;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function getHeaderAdminSsoToken(): array
    {
        return ['Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJhZG1pbkBleGFtcGxlLmNvbSIsInV1aWQiOiI1NTBlODQwMC1lMjliLTQxZDQtYTcxNi00NDY2NTU0NDAwMDAiLCJyb2xlcyI6WyJBRE1JTiJdLCJpYXQiOjE3ODU0NjQ1MjIsImV4cCI6NDkzOTE1MDkyMn0.Icw32NysVQiuU04fz-9THs3igCYNu4ID1XAqHhzvF0A'];
    }

    protected function getHeaderRelativeSsoToken(): array
    {
        return ['Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJyZWxhdGl2ZUBleGFtcGxlLmNvbSIsInV1aWQiOiI0NDRlODQwMC1lMjliLTQ0NTQtYTcxNi02eTY2NTU0NDAwMDAiLCJyb2xlcyI6WyJSRUxBVElWRSJdLCJpYXQiOjE3ODU4MTAxMjIsImV4cCI6NDkzOTQ5NjUyMn0.b2MAl59T64nEcR_Lh7TdmyAcZJUY7XaC7VnP4YIzn-c'];
    }

    protected function getHeaderUserSsoToken(): array
    {
        return ['Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1c2VyQGV4YW1wbGUuY29tIiwidXVpZCI6IjQ0NGU4NDAwLWUyOWItNDQ1NC1hNzE2LTQ0NjY1NTQ0MDAwMCIsInJvbGVzIjpbIlVTRVIiXSwiaWF0IjoxNzg1ODEwMDAzLCJleHAiOjQ5Mzk0OTY0MDN9.2Cinc0rbhe1lvvtDhJi6tidHtPxGL5Nfyxl38GmbFqs'];
    }

    protected function getHeaderAdminApiKey(): array
    {
        return ['Authorization' => 'Bearer 4pawb2A0kxKSQuUAMtbCjH6n3CBbAj8snUnFU0Zs'];
    }

    protected function getHeaderUserApiKey(): array
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
