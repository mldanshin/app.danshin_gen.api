<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PeopleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('all200Provider')]
    public function test_all200(string $request, callable $expected): void
    {
        $response = $this->getJson(route('people.all').$request, $this->getHeaderUserToken());

        $response->assertStatus(200)->assertJson($expected);
    }

    /**
     * @return array[]
     */
    public static function all200Provider(): array
    {
        return [
            [
                '',
                fn (AssertableJson $json) => $json->has(22)
                    ->has('0', fn ($json) => $json->where('id', 6)
                        ->where('surname', 'Burkina')
                        ->where('oldSurname', null)
                        ->where('name', 'Natalia')
                        ->where('patronymic', 'Vladimirovna')
                    )
                    ->has('1', fn ($json) => $json->where('id', 7)
                        ->where('surname', 'Danshin')
                        ->where('oldSurname', null)
                        ->where('name', 'Denis')
                        ->where('patronymic', 'Maksimovich')
                    )
                    ->has('7', fn ($json) => $json->where('id', 4)
                    ->where('surname', 'Danshina')
                    ->where('oldSurname.0', 'Pluta')
                    ->where('name', 'Tatyana')
                    ->where('patronymic', 'Ivanovna')
                    )
                    ->has('18', fn ($json) => $json->where('id', 11)
                    ->where('surname', 'Solovyova')
                    ->where('oldSurname', null)
                    ->where('name', 'Olga')
                    ->where('patronymic', 'Igorevna')
                    )
                    ->has('20', fn ($json) => $json->where('id', 19)
                    ->where('surname', null)
                    ->where('oldSurname', null)
                    ->where('name', null)
                    ->where('patronymic', 'Petrovich')
                    )
                    ->has('21', fn ($json) => $json->where('id', 20)
                    ->where('surname', null)
                    ->where('oldSurname', null)
                    ->where('name', null)
                    ->where('patronymic', null)
                    )
                    ->etc(),
            ],
            [
                '?order=&search=',
                fn (AssertableJson $json) => $json->has(22)
                    ->has('0', fn ($json) => $json->where('id', 6)
                    ->where('surname', 'Burkina')
                    ->where('oldSurname', null)
                    ->where('name', 'Natalia')
                    ->where('patronymic', 'Vladimirovna')
                    )
                    ->has('1', fn ($json) => $json->where('id', 7)
                        ->where('surname', 'Danshin')
                        ->where('oldSurname', null)
                        ->where('name', 'Denis')
                        ->where('patronymic', 'Maksimovich')
                    )
                    ->has('7', fn ($json) => $json->where('id', 4)
                    ->where('surname', 'Danshina')
                    ->where('oldSurname.0', 'Pluta')
                    ->where('name', 'Tatyana')
                    ->where('patronymic', 'Ivanovna')
                    )
                    ->has('18', fn ($json) => $json->where('id', 11)
                    ->where('surname', 'Solovyova')
                    ->where('oldSurname', null)
                    ->where('name', 'Olga')
                    ->where('patronymic', 'Igorevna')
                    )
                    ->has('20', fn ($json) => $json->where('id', 19)
                    ->where('surname', null)
                    ->where('oldSurname', null)
                    ->where('name', null)
                    ->where('patronymic', 'Petrovich')
                    )
                    ->has('21', fn ($json) => $json->where('id', 20)
                    ->where('surname', null)
                    ->where('oldSurname', null)
                    ->where('name', null)
                    ->where('patronymic', null)
                    )
                    ->etc(),
            ],
            [
                '?order=name',
                fn (AssertableJson $json) => $json->has(22)
                    ->has('0', fn ($json) => $json->where('id', 6)
                    ->where('surname', 'Burkina')
                    ->where('oldSurname', null)
                    ->where('name', 'Natalia')
                    ->where('patronymic', 'Vladimirovna')
                    )
                    ->has('1', fn ($json) => $json->where('id', 7)
                        ->where('surname', 'Danshin')
                        ->where('oldSurname', null)
                        ->where('name', 'Denis')
                        ->where('patronymic', 'Maksimovich')
                    )
                    ->has('7', fn ($json) => $json->where('id', 4)
                    ->where('surname', 'Danshina')
                    ->where('oldSurname.0', 'Pluta')
                    ->where('name', 'Tatyana')
                    ->where('patronymic', 'Ivanovna')
                    )
                    ->has('18', fn ($json) => $json->where('id', 11)
                    ->where('surname', 'Solovyova')
                    ->where('oldSurname', null)
                    ->where('name', 'Olga')
                    ->where('patronymic', 'Igorevna')
                    )
                    ->has('20', fn ($json) => $json->where('id', 19)
                    ->where('surname', null)
                    ->where('oldSurname', null)
                    ->where('name', null)
                    ->where('patronymic', 'Petrovich')
                    )
                    ->has('21', fn ($json) => $json->where('id', 20)
                    ->where('surname', null)
                    ->where('oldSurname', null)
                    ->where('name', null)
                    ->where('patronymic', null)
                    )
                    ->etc(),
            ],
            [
                '?order=age',
                fn (AssertableJson $json) => $json->has(22)
                    ->has('0', fn ($json) => $json->where('id', 1)
                    ->where('surname', 'Danshin')
                    ->where('oldSurname', null)
                    ->where('name', 'Pavel')
                    ->where('patronymic', 'Tikhonovich')
                    )
                    ->has('1', fn ($json) => $json->where('id', 2)
                        ->where('surname', 'Danshina')
                        ->where('oldSurname.0', 'Petrova')
                        ->where('name', 'Elizabeth')
                        ->where('patronymic', 'Dmitrievna')
                    )
                    ->has('11', fn ($json) => $json->where('id', 16)
                    ->where('surname', 'Sidorov')
                    ->where('oldSurname', null)
                    ->where('name', 'Maxim')
                    ->where('patronymic', 'Petrovich')
                    )
                    ->has('15', fn ($json) => $json->where('id', 12)
                    ->where('surname', 'Solovyov')
                    ->where('oldSurname', null)
                    ->where('name', 'Oleg')
                    ->where('patronymic', 'Igorevich')
                    )
                    ->has('17', fn ($json) => $json->where('id', 7)
                    ->where('surname', 'Danshin')
                    ->where('oldSurname', null)
                    ->where('name', 'Denis')
                    ->where('patronymic', 'Maksimovich')
                    )
                    ->has('18', fn ($json) => $json->where('id', 13)
                    ->where('surname', 'Petrenko')
                    ->where('oldSurname', null)
                    ->where('name', 'Elena')
                    ->where('patronymic', 'Sergeevna')
                    )
                    ->has('19', fn ($json) => $json->where('id', 21)
                    ->where('surname', null)
                    ->where('oldSurname', null)
                    ->where('name', 'Inna')
                    ->where('patronymic', null)
                    )
                    ->has('20', fn ($json) => $json->where('id', 19)
                    ->where('surname', null)
                    ->where('oldSurname', null)
                    ->where('name', null)
                    ->where('patronymic', 'Petrovich')
                    )
                    ->has('21', fn ($json) => $json->where('id', 20)
                    ->where('surname', null)
                    ->where('oldSurname', null)
                    ->where('name', null)
                    ->where('patronymic', null)
                    ),
            ],
            [
                '?search=Danshin     Pavel    Tikhonovich',
                fn (AssertableJson $json) => $json->has(1)
                    ->has('0', fn ($json) => $json->where('id', 1)
                    ->where('surname', 'Danshin')
                    ->where('oldSurname', null)
                    ->where('name', 'Pavel')
                    ->where('patronymic', 'Tikhonovich')
                    ),
            ],
            [
                '?search=Denis',
                fn (AssertableJson $json) => $json->has(2)
                    ->has('0', fn ($json) => $json->where('id', 7)
                        ->where('surname', 'Danshin')
                        ->where('oldSurname', null)
                        ->where('name', 'Denis')
                        ->where('patronymic', 'Maksimovich')
                    )
                    ->has('1', fn ($json) => $json->where('id', 17)
                        ->where('surname', 'Sidorov')
                        ->where('oldSurname', null)
                        ->where('name', 'Denis')
                        ->where('patronymic', 'Petrovich')
                    ),
            ],
            [
                '?search=Burkina Natalia',
                fn (AssertableJson $json) => $json->has(1)
                    ->has('0', fn ($json) => $json->where('id', 6)
                        ->where('surname', 'Burkina')
                        ->where('oldSurname', null)
                        ->where('name', 'Natalia')
                        ->where('patronymic', 'Vladimirovna')
                    ),
            ],
            [
                '?search=Natalia Vladimirovna',
                fn (AssertableJson $json) => $json->has(1)
                    ->has('0', fn ($json) => $json->where('id', 6)
                        ->where('surname', 'Burkina')
                        ->where('oldSurname', null)
                        ->where('name', 'Natalia')
                        ->where('patronymic', 'Vladimirovna')
                    ),
            ],
            [
                '?search=Nat Vlad',
                fn (AssertableJson $json) => $json->has(1)
                    ->has('0', fn ($json) => $json->where('id', 6)
                        ->where('surname', 'Burkina')
                        ->where('oldSurname', null)
                        ->where('name', 'Natalia')
                        ->where('patronymic', 'Vladimirovna')
                    ),
            ],
            [
                '?search=Pluta',
                fn (AssertableJson $json) => $json->has(1)
                    ->has('0', fn ($json) => $json->where('id', 4)
                        ->where('surname', 'Danshina')
                        ->where('oldSurname.0', 'Pluta')
                        ->where('name', 'Tatyana')
                        ->where('patronymic', 'Ivanovna')
                    ),
            ],
            [
                '?search=Pluta Tatyana',
                fn (AssertableJson $json) => $json->has(1)
                    ->has('0', fn ($json) => $json->where('id', 4)
                        ->where('surname', 'Danshina')
                        ->where('oldSurname.0', 'Pluta')
                        ->where('name', 'Tatyana')
                        ->where('patronymic', 'Ivanovna')
                    ),
            ],
            [
                '?search=Danshin Danshin Danshin Danshin',
                fn (AssertableJson $json) => $json->has(22),
            ],
        ];
    }

    public function test_all401(): void
    {
        $response = $this->getJson(route('people.all'));
        $response->assertStatus(401);
    }

    #[DataProvider('all422Provider')]
    public function test_all422(string $request): void
    {
        $response = $this->getJson(route('people.all').$request, $this->getHeaderUserToken());
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function all422Provider(): array
    {
        return [
            ['?order=fake'],
        ];
    }
}
