<?php

namespace Tests\Feature\Console\Commands;

use Tests\TestCase;

final class DirectoryDownloadClearTest extends TestCase
{
    public function test_success(): void
    {
        $this->artisan('download:clear')->assertExitCode(0);
    }
}
