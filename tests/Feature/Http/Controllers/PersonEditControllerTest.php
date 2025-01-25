<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage as StorageFacade;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PersonEditControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('edit200Provider')]
    public function test_edit200(string $id, callable $expected): void
    {
        $disk = StorageFacade::fake('public');
        $this->seedStorage($disk);

        $response = $this->getJson(route('person.edit', ['person' => $id]), $this->getHeaderAdminToken());
        $response->assertStatus(200)->assertJson($expected);
    }

    /**
     * @return array[]
     */
    public static function edit200Provider(): array
    {
        return [
            [
                '1',
                fn (AssertableJson $json) => $json->where('id', 1)
                    ->where('isUnavailable', true)
                    ->where('isLive', false)
                    ->where('gender', 2)
                    ->where('surname', 'Danshin')
                    ->where('name', 'Pavel')
                    ->where('oldSurname', [])
                    ->where('patronymic', 'Tikhonovich')
                    ->has('birthDate', fn ($json) => $json->where('hasUnknown', false)
                        ->where('isEmpty', false)
                        ->where('string', '1905-10-11')
                        ->where('year', '1905')
                        ->where('month', '10')
                        ->where('day', '11')
                    )
                    ->where('birthPlace', 'Novosibirsk')
                    ->has('deathDate', fn ($json) => $json->where('hasUnknown', false)
                    ->where('isEmpty', false)
                    ->where('string', '1986-02-04')
                    ->where('year', '1986')
                    ->where('month', '02')
                    ->where('day', '04')
                    )
                    ->where('burialPlace', 'Kemerovo')
                    ->where('note', null)
                    ->where('activities.0', 'Teacher')
                    ->where('emails', [])
                    ->has('internet.0', fn ($json) => $json->where('url', 'http://1.danshin.net')
                    ->where('name', 'Internet1')
                    )
                    ->has('internet.1', fn ($json) => $json->where('url', 'http://2.danshin.net')
                        ->where('name', 'Internet2')
                    )
                    ->where('phones', [])
                    ->has('residences.0', fn ($json) => $json->where('name', 'Kemerovo')
                        ->has('date', fn ($json) => $json->where('hasUnknown', false)
                            ->where('isEmpty', false)
                            ->where('string', '1986-01-01')
                            ->where('year', '1986')
                            ->where('month', '01')
                            ->where('day', '01')
                        )
                    )
                    ->has('residences.1', fn ($json) => $json->where('name', 'Mosk')
                        ->has('date', fn ($json) => $json->where('hasUnknown', false)
                            ->where('isEmpty', false)
                            ->where('string', '1970-01-01')
                            ->where('year', '1970')
                            ->where('month', '01')
                            ->where('day', '01')
                        )
                    )
                    ->where('parents', [])
                    ->has('marriages.0', fn ($json) => $json->etc()
                        ->where('role', 4)
                        ->where('soulmate', 2)
                        ->where('soulmateRole', 5)
                    )
                    ->has('photo.0', fn ($json) => $json->where('order', 1)
                        ->where('fileName', '1.webp')
                        ->where('date', null)
                    )
                    ->has('photo.1', fn ($json) => $json->where('order', 2)
                        ->where('fileName', '2.webp')
                        ->has('date', fn ($json) => $json->where('hasUnknown', false)
                            ->where('isEmpty', false)
                            ->where('string', '1985-01-01')
                            ->where('year', '1985')
                            ->where('month', '01')
                            ->where('day', '01')
                        )
                    )
                    ->has('photo.2', fn ($json) => $json->where('order', 3)
                        ->where('fileName', '3.webp')
                        ->has('date', fn ($json) => $json->where('hasUnknown', true)
                            ->where('isEmpty', false)
                            ->where('string', '1985-??-01')
                            ->where('year', '1985')
                            ->where('month', null)
                            ->where('day', '01')
                        )
                    )
                    ->where('hasPatronymic', true),
            ],
            [
                '5',
                fn (AssertableJson $json) => $json->where('id', 5)
                    ->where('isUnavailable', false)
                    ->where('isLive', true)
                    ->where('gender', 2)
                    ->where('surname', 'Danshin')
                    ->where('name', 'Maxim')
                    ->has('oldSurname.0', fn ($json) => $json->where('order', 1)
                        ->where('surname', 'Fake')
                    )
                    ->has('oldSurname.1', fn ($json) => $json->where('order', 2)
                        ->where('surname', 'AFake')
                    )
                    ->where('patronymic', 'Leonidovich')
                    ->has('birthDate', fn ($json) => $json->where('hasUnknown', false)
                        ->where('string', '1979-11-18')
                        ->where('isEmpty', false)
                        ->where('year', '1979')
                        ->where('month', '11')
                        ->where('day', '18')
                    )
                    ->where('birthPlace', 'Kemerovo')
                    ->where('deathDate', null)
                    ->where('burialPlace', null)
                    ->where('note', 'fakenote')
                    ->where('activities', [])
                    ->where('emails.0', 'mail@danshin.net')
                    ->where('internet', [])
                    ->where('phones.0', '9991112222')
                    ->where('residences', [])
                    ->has('parents.0', fn ($json) => $json->where('person', 3)
                        ->where('role', 2)
                    )
                    ->where('parents.0.role', 2)
                    ->has('parents.1', fn ($json) => $json->where('person', 4)
                        ->where('role', 3)
                    )
                    ->has('marriages.0', fn ($json) => $json->etc()
                        ->where('role', 4)
                        ->where('soulmate', 6)
                        ->where('soulmateRole', 5)
                    )
                    ->where('photo', [])
                    ->where('hasPatronymic', true),
            ],
            [
                '20',
                fn (AssertableJson $json) => $json->where('id', 20)
                    ->where('isUnavailable', false)
                    ->where('isLive', true)
                    ->where('gender', 3)
                    ->where('surname', null)
                    ->where('name', null)
                    ->where('oldSurname', [])
                    ->where('patronymic', null)
                    ->where('birthDate', null)
                    ->where('birthPlace', 'Kemerovo')
                    ->where('deathDate', null)
                    ->where('burialPlace', null)
                    ->where('note', null)
                    ->where('activities', [])
                    ->where('emails', [])
                    ->where('internet', [])
                    ->where('phones', [])
                    ->where('residences', [])
                    ->where('parents', [])
                    ->where('marriages', [])
                    ->where('photo', [])
                    ->where('hasPatronymic', true),
            ],
            [
                '22',
                fn (AssertableJson $json) => $json->where('id', 22)
                    ->where('isUnavailable', true)
                    ->where('isLive', true)
                    ->where('gender', 1)
                    ->where('surname', 'Fakefake')
                    ->where('name', 'Egor')
                    ->where('oldSurname', [])
                    ->where('patronymic', '')
                    ->has('birthDate', fn ($json) => $json->where('hasUnknown', true)
                        ->where('string', '????-05-01')
                        ->where('isEmpty', false)
                        ->where('year', null)
                        ->where('month', '05')
                        ->where('day', '01')
                    )
                    ->where('birthPlace', 'Kemerovo')
                    ->where('deathDate', null)
                    ->where('burialPlace', null)
                    ->where('note', null)
                    ->where('activities', [])
                    ->where('emails', [])
                    ->where('internet', [])
                    ->where('phones', [])
                    ->where('residences', [])
                    ->where('parents', [])
                    ->where('marriages', [])
                    ->where('photo', [])
                    ->where('hasPatronymic', false),
            ],
        ];
    }

    public function test_edit401(): void
    {
        $response = $this->getJson(route('person.edit', ['person' => '1']));
        $response->assertStatus(401);
    }

    public function test_edit403(): void
    {
        $response = $this->getJson(route('person.edit', ['person' => '1']), $this->getHeaderUserToken());
        $response->assertStatus(403);
    }

    #[DataProvider('edit404Provider')]
    public function test_edit404(string $id): void
    {
        $response = $this->getJson(route('person.edit', ['person' => $id]), $this->getHeaderAdminToken());
        $response->assertStatus(404);
    }

    /**
     * @return array[]
     */
    public static function edit404Provider(): array
    {
        return [
            [
                '99999',
            ],
            [
                'fake',
            ],
        ];
    }
}
