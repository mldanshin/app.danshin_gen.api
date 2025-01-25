<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage as StorageFacade;
use Tests\TestCase;

final class DownloadPhotoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test200(): void
    {
        $disk = StorageFacade::fake('public');
        $this->seedStorage($disk);
        $this->setConfigFakeDisk();

        $response = $this->get(route('download.photo'), $this->getHeaderUserToken());
        $response->assertDownload('danshin_genealogy_photo.zip');
        $disk->assertExists('download/danshin_genealogy_photo.zip');
    }

    public function test204(): void
    {
        $disk = StorageFacade::fake('public');
        $this->setConfigFakeDisk();
        $this->clearStorage($disk);

        $response = $this->get(route('download.photo'), $this->getHeaderUserToken());
        $response->assertStatus(204);
    }

    public function test401(): void
    {
        $response = $this->getJson(route('download.photo'));
        $response->assertStatus(401);
    }
}
