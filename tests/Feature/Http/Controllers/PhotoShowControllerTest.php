<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage as StorageFacade;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PhotoShowControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('response202Provider')]
    public function test200(string $personId, string $fileName): void
    {
        $disk = StorageFacade::fake('public');
        $this->seedStorage($disk);
        $this->setConfigFakeDisk();

        $response = $this->get(
            route('photo.show', ['personId' => $personId, 'fileName' => $fileName]),
            $this->getHeaderUserToken()
        );
        $response->assertDownload('3.webp');
    }

    /**
     * @return array[]
     */
    public static function response202Provider(): array
    {
        return [
            ['1', '3.webp'],
            ['2', '3.webp'],
        ];
    }

    public function test401(): void
    {
        $response = $this->getJson(route('photo.show', ['personId' => 1, 'fileName' => 'fake.png']));
        $response->assertStatus(401);
    }

    #[DataProvider('response404Provider')]
    public function test404(string $personId, string $fileName): void
    {
        $disk = StorageFacade::fake('public');
        $this->seedStorage($disk);
        $this->setConfigFakeDisk();

        $response = $this->get(
            route('photo.show', ['personId' => $personId, 'fileName' => $fileName]),
            $this->getHeaderUserToken()
        );
        $response->assertStatus(404);
    }

    /**
     * @return array[]
     */
    public static function response404Provider(): array
    {
        return [
            ['1', 'fake999.png'],
            ['9999', '3.png'],
        ];
    }
}
