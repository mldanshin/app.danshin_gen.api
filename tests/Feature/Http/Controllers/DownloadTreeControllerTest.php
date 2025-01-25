<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage as StorageFacade;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class DownloadTreeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('response200Provider')]
    public function test200(string $personId, string $request, string $expectedFileName): void
    {
        $disk = StorageFacade::fake('public');
        $this->seedStorage($disk);
        $this->setConfigFakeDisk();

        $response = $this->get(route('download.tree', ['id' => $personId]).$request, $this->getHeaderUserToken());
        $response->assertDownload($expectedFileName);
        $disk->assertExists("download/$expectedFileName");
    }

    /**
     * @return array[]
     */
    public static function response200Provider(): array
    {
        return [
            ['3', '?parent_id=1', 'danshin_genealogy_tree_3_1.svg'],
            ['7', '?parent_id=5', 'danshin_genealogy_tree_7_5.svg'],
            ['7', '?parent_id=', 'danshin_genealogy_tree_7.svg'],
            ['1', '', 'danshin_genealogy_tree_1.svg'],
            ['7', '?parent_id=6', 'danshin_genealogy_tree_7_6.svg'],
        ];
    }

    #[DataProvider('response302Provider')]
    public function test302(string $request): void
    {
        $response = $this->get(route('download.tree', ['id' => 1]).$request, $this->getHeaderUserToken());
        $response->assertStatus(302);
    }

    /**
     * @return array[]
     */
    public static function response302Provider(): array
    {
        return [
            ['?parent_id=fake'],
            ['?parent_id=2.3'],
        ];
    }

    public function test401(): void
    {
        $response = $this->getJson(route('download.tree', ['id' => 1]));
        $response->assertStatus(401);
    }

    #[DataProvider('response404Provider')]
    public function test404(string $personId, string $request): void
    {
        $disk = StorageFacade::fake('public');
        $this->seedStorage($disk);
        $this->setConfigFakeDisk();

        $response = $this->get(route('download.tree', ['id' => $personId]).$request, $this->getHeaderUserToken());
        $response->assertStatus(404);
    }

    /**
     * @return array[]
     */
    public static function response404Provider(): array
    {
        return [
            ['1', '?parent_id=1'],
            ['90', '?parent_id=1'],
            ['5', '?parent_id=7'],
            ['fake', ''],
        ];
    }
}
