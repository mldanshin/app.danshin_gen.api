<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage as StorageFacade;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PhotoStoreTempControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('response202Provider')]
    public function test200($file): void
    {
        $disk = StorageFacade::fake('public');
        $this->seedStorage($disk);
        $this->setConfigFakeDisk();

        $this->assertCount(3, $disk->allFiles('photo_temp'));

        $response = $this->postJson(
            route('photo.temp.store'),
            [
                'photo' => $file,
            ],
            headers: $this->getHeaderAdminToken()
        );
        $this->assertCount(4, $disk->allFiles('photo_temp'));
    }

    /**
     * @return array[]
     */
    public static function response202Provider(): array
    {
        return [
            [
                UploadedFile::fake()->image('test1.png'),
            ],
            [
                UploadedFile::fake()->image('test1.webp'),
            ],
        ];
    }

    public function test401(): void
    {
        $response = $this->postJson(route('photo.temp.store'));
        $response->assertStatus(401);
    }
}
