<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage as StorageFacade;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PhotoListByPersonControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('response202Provider')]
    public function test200(string $personId, callable $expected): void
    {
        $disk = StorageFacade::fake('public');
        $this->seedStorage($disk);
        $this->setConfigFakeDisk();

        $response = $this->get(
            route('photo.list', ['personId' => $personId]),
            $this->getHeaderUserToken()
        );
        $response->assertStatus(200)->assertJson($expected);
    }

    /**
     * @return array[]
     */
    public static function response202Provider(): array
    {
        return [
            [
                '1',
                'expected' => fn (AssertableJson $json) => $json->has('0', fn ($json) => $json->where('order', 1)
                    ->where('fileName', '1.webp')
                    ->where('date', null)
                )
                    ->has('1', fn ($json) => $json->where('order', 2)
                    ->where('fileName', '2.webp')
                    ->has('date', fn ($json) => $json->where('hasUnknown', false)
                        ->where('isEmpty', false)
                        ->where('string', '1985-01-01')
                        ->where('year', '1985')
                        ->where('month', '01')
                        ->where('day', '01')
                    )
                    )
                    ->has('2', fn ($json) => $json->where('order', 3)
                        ->where('fileName', '3.webp')
                        ->has('date', fn ($json) => $json->where('hasUnknown', true)
                            ->where('isEmpty', false)
                            ->where('string', '1985-??-01')
                            ->where('year', '1985')
                            ->where('month', null)
                            ->where('day', '01')
                        )
                    ),
            ],
        ];
    }

    public function test401(): void
    {
        $response = $this->getJson(route('photo.list', ['personId' => 1]));
        $response->assertStatus(401);
    }

    #[DataProvider('response404Provider')]
    public function test404(string $personId): void
    {
        $disk = StorageFacade::fake('public');
        $this->seedStorage($disk);
        $this->setConfigFakeDisk();

        $response = $this->get(
            route('photo.list', ['personId' => $personId]),
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
            ['9999'],
        ];
    }
}
