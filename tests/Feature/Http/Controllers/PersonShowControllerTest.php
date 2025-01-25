<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\DateTimeCustom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage as StorageFacade;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PersonShowControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('show200Provider')]
    public function test_show200(int $person, string $request, callable $expected): void
    {
        DateTimeCustom::setMockNow('2023-08-21');

        $disk = StorageFacade::fake('public');
        $this->seedStorage($disk);
        $this->setConfigFakeDisk();

        $response = $this->getJson(
            route('person.show', ['id' => $person]).$request, $this->getHeaderUserToken()
        );
        $response->assertStatus(200)->assertJson($expected);
    }

    /**
     * @return array[]
     */
    public static function show200Provider(): array
    {
        return [
            [
                'person' => 1,
                'request' => '',
                'expected' => fn (AssertableJson $json) => $json->where('id', 1)
                    ->where('isUnavailable', true)
                    ->where('gender', 2)
                    ->where('surname', 'Danshin')
                    ->where('oldSurname', null)
                    ->where('name', 'Pavel')
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
                    ->where('emails', null)
                    ->has('internet.0', fn ($json) => $json->where('url', 'http://1.danshin.net')
                    ->where('name', 'Internet1')
                    )
                    ->has('internet.1', fn ($json) => $json->where('url', 'http://2.danshin.net')
                        ->where('name', 'Internet2')
                    )
                    ->where('phones', null)
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
                        ->etc()
                        ->has('date', fn ($json) => $json->where('hasUnknown', false)
                            ->where('isEmpty', false)
                            ->where('string', '1970-01-01')
                            ->where('year', '1970')
                            ->where('month', '01')
                            ->where('day', '01')
                        )
                    )
                    ->where('parents', null)
                    ->has('marriages.0.soulmate', fn ($json) => $json->where('id', 2)
                        ->where('surname', 'Danshina')
                        ->where('oldSurname.0', 'Petrova')
                        ->where('name', 'Elizabeth')
                        ->where('patronymic', 'Dmitrievna')
                    )
                    ->where('marriages.0.role', 5)
                    ->has('children.0', fn ($json) => $json->where('id', 3)
                        ->where('surname', 'Danshin')
                        ->where('oldSurname', null)
                        ->where('name', 'Leonid')
                        ->where('patronymic', 'Pavlovich')
                    )
                    ->where('brothersSisters', null)
                    ->has('age', fn ($json) => $json->where('y', 80)
                        ->where('m', 3)
                        ->where('d', 24)
                        ->etc()
                    )
                    ->has('deathDateInterval', fn ($json) => $json->where('y', 37)
                    ->where('m', 6)
                    ->where('d', 17)
                    ->etc()
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
                    ->where('isLive', false),
            ],
            [
                'person' => 5,
                'request' => '',
                'expected' => fn (AssertableJson $json) => $json->where('id', 5)
                    ->where('isUnavailable', false)
                    ->where('gender', 2)
                    ->where('surname', 'Danshin')
                    ->where('oldSurname.0', 'Fake')
                    ->where('oldSurname.1', 'AFake')
                    ->where('name', 'Maxim')
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
                    ->where('activities', null)
                    ->where('emails.0', 'mail@danshin.net')
                    ->where('internet', null)
                    ->where('phones.0', '9991112222')
                    ->where('residences', null)
                    ->has('parents.0.person', fn ($json) => $json->where('id', 3)
                    ->where('surname', 'Danshin')
                    ->where('oldSurname', null)
                    ->where('name', 'Leonid')
                    ->where('patronymic', 'Pavlovich')
                    )
                    ->where('parents.0.role', 2)
                    ->has('parents.1.person', fn ($json) => $json->where('id', 4)
                        ->where('surname', 'Danshina')
                        ->where('oldSurname.0', 'Pluta')
                        ->where('name', 'Tatyana')
                        ->where('patronymic', 'Ivanovna')
                    )
                    ->where('parents.1.role', 3)
                    ->has('marriages.0.soulmate', fn ($json) => $json->where('id', 6)
                        ->where('surname', 'Burkina')
                        ->where('oldSurname', null)
                        ->where('name', 'Natalia')
                        ->where('patronymic', 'Vladimirovna')
                    )
                    ->where('marriages.0.role', 5)
                    ->has('brothersSisters.0', fn ($json) => $json->where('id', 8)
                        ->where('surname', 'Danshin')
                        ->where('oldSurname', null)
                        ->where('name', 'Egor')
                        ->where('patronymic', 'Leonidovich')
                    )
                    ->has('brothersSisters.1', fn ($json) => $json->where('id', 9)
                        ->where('surname', 'Solovyova')
                        ->where('oldSurname.0', 'Danshin')
                        ->where('name', 'Oksana')
                        ->where('patronymic', 'Leonidovna')
                    )
                    ->has('children.0', fn ($json) => $json->where('id', 7)
                        ->where('surname', 'Danshin')
                        ->where('oldSurname', null)
                        ->where('name', 'Denis')
                        ->where('patronymic', 'Maksimovich')
                    )
                    ->has('age', fn ($json) => $json->where('y', 43)
                        ->where('m', 9)
                        ->where('d', 3)
                        ->etc()
                    )
                    ->where('deathDateInterval', null)
                    ->where('photo', null)
                    ->where('isLive', true),
            ],
            [
                'person' => 7,
                'request' => '',
                'expected' => fn (AssertableJson $json) => $json->where('id', 7)
                    ->where('isUnavailable', false)
                    ->where('gender', 2)
                    ->where('surname', 'Danshin')
                    ->where('oldSurname', null)
                    ->where('name', 'Denis')
                    ->where('patronymic', 'Maksimovich')
                    ->where('birthDate', null)
                    ->where('birthPlace', null)
                    ->where('deathDate', null)
                    ->where('burialPlace', null)
                    ->where('note', null)
                    ->where('activities', null)
                    ->where('emails.0', 'den@fakemail.ru')
                    ->where('internet', null)
                    ->where('phones.0', '9993332222')
                    ->where('residences', null)
                    ->has('parents.0.person', fn ($json) => $json->where('id', 5)
                    ->where('surname', 'Danshin')
                    ->where('oldSurname.0', 'Fake')
                    ->where('oldSurname.1', 'AFake')
                    ->where('name', 'Maxim')
                    ->where('patronymic', 'Leonidovich')
                    )
                    ->where('parents.0.role', 2)
                    ->has('parents.1.person', fn ($json) => $json->where('id', 6)
                        ->where('surname', 'Burkina')
                        ->where('oldSurname', null)
                        ->where('name', 'Natalia')
                        ->where('patronymic', 'Vladimirovna')
                    )
                    ->where('parents.1.role', 3)
                    ->where('marriages', null)
                    ->where('brothersSisters', null)
                    ->where('children', null)
                    ->where('age', null)
                    ->where('deathDateInterval', null)
                    ->where('photo', null)
                    ->where('isLive', true),
            ],
            [
                'person' => 5,
                'request' => '?date=2020-01-01',
                'expected' => fn (AssertableJson $json) => $json->where('id', 5)
                    ->where('isUnavailable', false)
                    ->where('gender', 2)
                    ->where('surname', 'Danshin')
                    ->where('oldSurname.0', 'Fake')
                    ->where('oldSurname.1', 'AFake')
                    ->where('name', 'Maxim')
                    ->where('patronymic', 'Leonidovich')
                    ->has('birthDate', fn ($json) => $json->where('hasUnknown', false)
                        ->where('isEmpty', false)
                        ->where('string', '1979-11-18')
                        ->where('year', '1979')
                        ->where('month', '11')
                        ->where('day', '18')
                    )
                    ->where('birthPlace', 'Kemerovo')
                    ->where('deathDate', null)
                    ->where('burialPlace', null)
                    ->where('note', 'fakenote')
                    ->where('activities', null)
                    ->where('emails.0', 'mail@danshin.net')
                    ->where('internet', null)
                    ->where('phones.0', '9991112222')
                    ->where('residences', null)
                    ->has('parents.0.person', fn ($json) => $json->where('id', 3)
                    ->where('surname', 'Danshin')
                    ->where('oldSurname', null)
                    ->where('name', 'Leonid')
                    ->where('patronymic', 'Pavlovich')
                    )
                    ->where('parents.0.role', 2)
                    ->has('parents.1.person', fn ($json) => $json->where('id', 4)
                        ->where('surname', 'Danshina')
                        ->where('oldSurname.0', 'Pluta')
                        ->where('name', 'Tatyana')
                        ->where('patronymic', 'Ivanovna')
                    )
                    ->where('parents.1.role', 3)
                    ->has('marriages.0.soulmate', fn ($json) => $json->where('id', 6)
                        ->where('surname', 'Burkina')
                        ->where('oldSurname', null)
                        ->where('name', 'Natalia')
                        ->where('patronymic', 'Vladimirovna')
                    )
                    ->where('marriages.0.role', 5)
                    ->has('brothersSisters.0', fn ($json) => $json->where('id', 8)
                        ->where('surname', 'Danshin')
                        ->where('oldSurname', null)
                        ->where('name', 'Egor')
                        ->where('patronymic', 'Leonidovich')
                    )
                    ->has('brothersSisters.1', fn ($json) => $json->where('id', 9)
                        ->where('surname', 'Solovyova')
                        ->where('oldSurname.0', 'Danshin')
                        ->where('name', 'Oksana')
                        ->where('patronymic', 'Leonidovna')
                    )
                    ->has('children.0', fn ($json) => $json->where('id', 7)
                        ->where('surname', 'Danshin')
                        ->where('oldSurname', null)
                        ->where('name', 'Denis')
                        ->where('patronymic', 'Maksimovich')
                    )
                    ->has('age', fn ($json) => $json->where('y', 43)
                        ->where('m', 9)
                        ->where('d', 3)
                        ->etc()
                    )
                    ->where('deathDateInterval', null)
                    ->where('photo', null)
                    ->where('isLive', true),
            ],
        ];
    }

    public function test_show401(): void
    {
        $this->setConfigFakeDisk();

        $response = $this->getJson(route('person.show', ['id' => 1]));
        $response->assertStatus(401);
    }

    #[DataProvider('show404Provider')]
    public function test_show404(?string $id, string $request): void
    {
        $this->setConfigFakeDisk();

        $response = $this->getJson(route('person.show', ['id' => $id]).$request, $this->getHeaderUserToken());
        $response->assertStatus(404);
    }

    /**
     * @return array[]
     */
    public static function show404Provider(): array
    {
        return [
            [
                '99999',
                '',
            ],
            [
                'fake',
                '?date=2023-09-01',
            ],
        ];
    }

    #[DataProvider('show422Provider')]
    public function test_show422(?string $id, string $request): void
    {
        $this->setConfigFakeDisk();

        $response = $this->getJson(route('person.show', ['id' => $id]).$request, $this->getHeaderUserToken());
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function show422Provider(): array
    {
        return [
            [
                '1',
                '?date=2023090144',
            ],
            [
                '1',
                '?date=fake',
            ],
        ];
    }
}
