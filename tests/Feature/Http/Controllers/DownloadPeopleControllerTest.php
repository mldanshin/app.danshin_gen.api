<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage as StorageFacade;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class DownloadPeopleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('response200Provider')]
    public function test200(string $request): void
    {
        $this->setConfigFakeDisk();
        $disk = StorageFacade::fake('public');
        $this->seedStorage($disk);

        $response = $this->get(route('download.people').$request, $this->getHeaderUserToken());
        $response->assertDownload('danshin_genealogy_people.pdf');
        $disk->assertExists('download/danshin_genealogy_people.pdf');
    }

    /**
     * @return array[]
     */
    public static function response200Provider(): array
    {
        return [
            ['?type=pdf'],
        ];
    }

    #[DataProvider('response302Provider')]
    public function test302(string $request): void
    {
        $response = $this->get(route('download.people').$request, $this->getHeaderUserToken());
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
        $response = $this->getJson(route('download.people'));
        $response->assertStatus(401);
    }

    #[DataProvider('response404Provider')]
    public function test404(string $request): void
    {
        $response = $this->get(route('download.people').$request, $this->getHeaderUserToken());
        $response->assertStatus(404);
    }

    /**
     * @return array[]
     */
    public static function response404Provider(): array
    {
        return [
            ['?type=fake'],
        ];
    }
}
