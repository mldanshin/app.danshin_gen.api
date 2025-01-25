<?php

namespace Tests\Feature\Console\Commands;

use Tests\TestCase;

final class PhotoTempClearTest extends TestCase
{
    public function test_success(): void
    {
        $this->artisan('photo:clear')->assertExitCode(0);
    }
}
