<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\DateTimeCustom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class ParentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_role_all200(): void
    {
        $expected = fn (AssertableJson $json) => $json->where(1, 'undefined')
            ->where(2, 'father')
            ->where(3, 'mother');

        $response = $this->getJson(route('parent.roles.all'), $this->getHeaderAdminToken());
        $response->assertStatus(200)->assertJson($expected);
    }

    public function test_role_all401(): void
    {
        $response = $this->getJson(route('parent.roles.all'));
        $response->assertStatus(401);
    }

    public function test_role_all403(): void
    {
        $response = $this->getJson(route('parent.roles.all'), $this->getHeaderUserToken());
        $response->assertStatus(403);
    }

    #[DataProvider('possible200Provider')]
    public function test_possible200(array $request, callable $expected): void
    {
        $response = $this->getJson(
            route('parent.possible', $request),
            $this->getHeaderAdminToken()
        );
        $response->assertStatus(200)->assertJson($expected);
    }

    /**
     * @return array[]
     */
    public static function possible200Provider(): array
    {
        return [
            [
                [
                    'person_id' => 2,
                    'birth_date' => '1909-09-04',
                    'role_id' => 2,
                    'mariages' => [
                        [
                            'person' => 1,
                        ],
                    ],
                ],
                fn (AssertableJson $json) => $json->has(3)
                    ->has('0', fn ($json) => $json->where('id', 19)
                        ->where('surname', null)
                        ->where('oldSurname', null)
                        ->where('name', null)
                        ->where('patronymic', 'Petrovich')
                    )
                    ->has('1', fn ($json) => $json->where('id', 7)
                        ->where('surname', 'Danshin')
                        ->where('oldSurname', null)
                        ->where('name', 'Denis')
                        ->where('patronymic', 'Maksimovich')
                    )
                    ->has('2', fn ($json) => $json->where('id', 22)
                    ->where('surname', 'Fakefake')
                    ->where('oldSurname', null)
                    ->where('name', 'Egor')
                    ->where('patronymic', '')
                    ),
            ],
            [
                [
                    'person_id' => 2,
                    'birth_date' => '1909-09-04',
                    'role_id' => 3,
                    'mariages' => [
                    [
                        'person' => 1,
                    ],
                    ],
                ],
                fn (AssertableJson $json) => $json->has(4)
                    ->has('0', fn ($json) => $json->where('id', 20)
                    ->where('surname', null)
                    ->where('oldSurname', null)
                    ->where('name', null)
                    ->where('patronymic', null)
                    )
                    ->has('1', fn ($json) => $json->where('id', 21)
                        ->where('surname', null)
                        ->where('oldSurname', null)
                        ->where('name', 'Inna')
                        ->where('patronymic', null)
                    )
                    ->has('2', fn ($json) => $json->where('id', 22)
                        ->where('surname', 'Fakefake')
                        ->where('oldSurname', null)
                        ->where('name', 'Egor')
                        ->where('patronymic', '')
                    )
                    ->has('3', fn ($json) => $json->where('id', 13)
                    ->where('surname', 'Petrenko')
                    ->where('oldSurname', null)
                    ->where('name', 'Elena')
                    ->where('patronymic', 'Sergeevna')
                    ),
            ],
            [
                [
                    'person_id' => 2,
                    'birth_date' => '1909-09-04',
                    'role_id' => 1,
                    'mariages' => [
                    [
                        'person' => 1,
                    ],
                    ],
                ],
                fn (AssertableJson $json) => $json->has(6)
                    ->has('0', fn ($json) => $json->where('id', 20)
                    ->where('surname', null)
                    ->where('oldSurname', null)
                    ->where('name', null)
                    ->where('patronymic', null)
                    )
                    ->has('1', fn ($json) => $json->where('id', 19)
                        ->where('surname', null)
                        ->where('oldSurname', null)
                        ->where('name', null)
                        ->where('patronymic', 'Petrovich')
                    )
                    ->has('2', fn ($json) => $json->where('id', 21)
                        ->where('surname', null)
                        ->where('oldSurname', null)
                        ->where('name', 'Inna')
                        ->where('patronymic', null)
                    )
                    ->has('3', fn ($json) => $json->where('id', 7)
                        ->where('surname', 'Danshin')
                        ->where('oldSurname', null)
                        ->where('name', 'Denis')
                        ->where('patronymic', 'Maksimovich')
                    )
                    ->has('4', fn ($json) => $json->where('id', 22)
                    ->where('surname', 'Fakefake')
                    ->where('oldSurname', null)
                    ->where('name', 'Egor')
                    ->where('patronymic', '')
                    )
                    ->has('5', fn ($json) => $json->where('id', 13)
                    ->where('surname', 'Petrenko')
                    ->where('oldSurname', null)
                    ->where('name', 'Elena')
                    ->where('patronymic', 'Sergeevna')
                    ),
            ],
            [
                [
                    'person_id' => 7,
                    'birth_date' => '',
                    'role_id' => 3,
                    'mariages' => [

                    ],
                ],
                fn (AssertableJson $json) => $json->has(12)
                    ->has('0', fn ($json) => $json->where('id', 20)
                    ->where('surname', null)
                    ->where('oldSurname', null)
                    ->where('name', null)
                    ->where('patronymic', null)
                    )
                    ->has('1', fn ($json) => $json->where('id', 21)
                        ->where('surname', null)
                        ->where('oldSurname', null)
                        ->where('name', 'Inna')
                        ->where('patronymic', null)
                    )
                    ->has('2', fn ($json) => $json->where('id', 6)
                        ->where('surname', 'Burkina')
                        ->where('oldSurname', null)
                        ->where('name', 'Natalia')
                        ->where('patronymic', 'Vladimirovna')
                    )
                    ->has('4', fn ($json) => $json->where('id', 4)
                        ->where('surname', 'Danshina')
                        ->where('oldSurname.0', 'Pluta')
                        ->where('name', 'Tatyana')
                        ->where('patronymic', 'Ivanovna')
                    )
                    ->has('11', fn ($json) => $json->where('id', 11)
                    ->where('surname', 'Solovyova')
                    ->where('oldSurname', null)
                    ->where('name', 'Olga')
                    ->where('patronymic', 'Igorevna')
                    )
                    ->etc(),
            ],
            [
                [
                    'person_id' => 22,
                    'birth_date' => '',
                    'role_id' => 1,
                    'mariages' => [

                    ],
                ],
                fn (AssertableJson $json) => $json->has(21)
                    ->has('0', fn ($json) => $json->where('id', 20)
                    ->where('surname', null)
                    ->where('oldSurname', null)
                    ->where('name', null)
                    ->where('patronymic', null)
                    )
                    ->has('1', fn ($json) => $json->where('id', 19)
                        ->where('surname', null)
                        ->where('oldSurname', null)
                        ->where('name', null)
                        ->where('patronymic', 'Petrovich')
                    )
                    ->has('2', fn ($json) => $json->where('id', 21)
                        ->where('surname', null)
                        ->where('oldSurname', null)
                        ->where('name', 'Inna')
                        ->where('patronymic', null)
                    )
                    ->has('3', fn ($json) => $json->where('id', 6)
                        ->where('surname', 'Burkina')
                        ->where('oldSurname', null)
                        ->where('name', 'Natalia')
                        ->where('patronymic', 'Vladimirovna')
                    )
                    ->has('4', fn ($json) => $json->where('id', 7)
                        ->where('surname', 'Danshin')
                        ->where('oldSurname', null)
                        ->where('name', 'Denis')
                        ->where('patronymic', 'Maksimovich')
                    )
                    ->has('10', fn ($json) => $json->where('id', 4)
                    ->where('surname', 'Danshina')
                    ->where('oldSurname.0', 'Pluta')
                    ->where('name', 'Tatyana')
                    ->where('patronymic', 'Ivanovna')
                    )
                    ->has('18', fn ($json) => $json->where('id', 12)
                    ->where('surname', 'Solovyov')
                    ->where('oldSurname', null)
                    ->where('name', 'Oleg')
                    ->where('patronymic', 'Igorevich')
                    )
                    ->has('19', fn ($json) => $json->where('id', 9)
                    ->where('surname', 'Solovyova')
                    ->where('oldSurname.0', 'Danshin')
                    ->where('name', 'Oksana')
                    ->where('patronymic', 'Leonidovna')
                    )
                    ->has('20', fn ($json) => $json->where('id', 11)
                    ->where('surname', 'Solovyova')
                    ->where('oldSurname', null)
                    ->where('name', 'Olga')
                    ->where('patronymic', 'Igorevna')
                    )
                    ->etc(),
            ],
            [
                [
                    'role_id' => 2,
                ],
                fn (AssertableJson $json) => $json->has(11)
                    ->has('0', fn ($json) => $json->where('id', 19)
                    ->where('surname', null)
                    ->where('oldSurname', null)
                    ->where('name', null)
                    ->where('patronymic', 'Petrovich')
                    )
                    ->has('10', fn ($json) => $json->where('id', 10)
                        ->where('surname', 'Solovyov')
                        ->where('oldSurname', null)
                        ->where('name', 'Igor')
                        ->where('patronymic', 'Ivanovich')
                    )
                    ->etc(),
            ],
            [
                [
                    'birth_date' => '1888-09-11',
                    'role_id' => 3,
                ],
                fn (AssertableJson $json) => $json->has(4)
                    ->has('0', fn ($json) => $json->where('id', 20)
                    ->where('surname', null)
                    ->where('oldSurname', null)
                    ->where('name', null)
                    ->where('patronymic', null)
                    )
                    ->has('3', fn ($json) => $json->where('id', 13)
                        ->where('surname', 'Petrenko')
                        ->where('oldSurname', null)
                        ->where('name', 'Elena')
                        ->where('patronymic', 'Sergeevna')
                    )
                    ->etc(),
            ],
        ];
    }

    public function test_possible401(): void
    {
        $response = $this->getJson(
            route('parent.possible', ['personId' => '1', 'roleParent' => '2'])
        );
        $response->assertStatus(401);
    }

    public function test_possible403(): void
    {
        $response = $this->getJson(
            route('parent.possible', ['personId' => '1', 'roleParent' => '2']),
            $this->getHeaderUserToken()
        );
        $response->assertStatus(403);
    }

    #[DataProvider('possible422Provider')]
    public function test_possible422(array $request): void
    {
        DateTimeCustom::setMockNow('2019-08-21');

        $response = $this->getJson(
            route('parent.possible', $request),
            $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function possible422Provider(): array
    {
        return [
            [
                [
                    'person_id' => 'fake',
                    'role_id' => 1,
                ],
            ],
            [
                [
                    'person_id' => 99,
                    'role_id' => 1,
                ],
            ],
            [
                [
                    'birth_date' => 'fake',
                    'role_id' => 1,
                ],
            ],
            [
                [
                    'birth_date' => '1222-13-01',
                    'role_id' => 1,
                ],
            ],
            [
                [
                    'birth_date' => '2020-12-01',
                    'role_id' => 1,
                ],
            ],
            [
                [
                    'role_id' => null,
                ],
            ],
            [
                [
                    'role_id' => 'fake',
                ],
            ],
            [
                [
                    'role_id' => 99,
                ],
            ],
            [
                [
                    'role_id' => 1,
                    'mariages' => [
                        [
                            'person' => 'fake',
                        ],
                    ],
                ],
            ],
            [
                [
                    'role_id' => 1,
                    'mariages' => [
                        [
                            'person' => 1,
                        ],
                        [
                            'person' => 1,
                        ],
                    ],
                ],
            ],
            [
                [
                    'role_id' => 1,
                    'mariages' => [
                        [
                            'person' => 99,
                        ],
                    ],
                ],
            ],
            [
                [
                    'person_id' => 2,
                    'role_id' => 1,
                    'mariages' => [
                        [
                            'person' => 2,
                        ],
                    ],
                ],
            ],
        ];
    }
}
