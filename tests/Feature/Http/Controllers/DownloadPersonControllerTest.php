<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage as StorageFacade;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class DownloadPersonControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('response200Provider')]
    public function test200(int $id, string $request): void
    {
        $disk = StorageFacade::fake('public');
        $this->seedStorage($disk);
        $this->setConfigFakeDisk();

        $response = $this->get(route('download.person', ['id' => $id]).$request, $this->getHeaderUserToken());
        $response->assertDownload("danshin_genealogy_person_$id.pdf");
        $disk->assertExists("download/danshin_genealogy_person_$id.pdf");
    }

    /**
     * @return array[]
     */
    public static function response200Provider(): array
    {
        return [
            [1, '?type=pdf'],
        ];
    }

    #[DataProvider('response302Provider')]
    public function test302(string $request): void
    {
        $response = $this->get(route('download.person', ['id' => 1]).$request, $this->getHeaderUserToken());
        $response->assertStatus(302);
    }

    /**
     * @return array[]
     */
    public static function response302Provider(): array
    {
        return [
            ['?type='],
            [''],
        ];
    }

    public function test401(): void
    {
        $response = $this->getJson(route('download.person', ['id' => 1]));
        $response->assertStatus(401);
    }

    #[DataProvider('response404Provider')]
    public function test404(int $id, string $request): void
    {
        $disk = StorageFacade::fake('public');
        $this->seedStorage($disk);
        $this->setConfigFakeDisk();

        $response = $this->get(route('download.person', ['id' => $id]).$request, $this->getHeaderUserToken());
        $response->assertStatus(404);
    }

    /**
     * @return array[]
     */
    public static function response404Provider(): array
    {
        return [
            [999, '?type=pdf'],
            [1, '?type=fake'],
        ];
    }
}
