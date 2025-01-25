<?php

namespace Tests\Feature\Http\Controllers\Date;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class DateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_all200(): void
    {
        $expected = fn (AssertableJson $json) => $json->has(21)
            ->has('0', fn ($json) => $json
                ->has('date', fn ($json) => $json->where('string', '1905-10-11')
                    ->etc()
                )
                ->has('person', fn ($json) => $json->where('id', 1)
                    ->where('surname', 'Danshin')
                    ->where('oldSurname', null)
                    ->where('name', 'Pavel')
                    ->where('patronymic', 'Tikhonovich')
                )
                ->where('type', 1)
            )
            ->has('19', fn ($json) => $json
                ->has('date', fn ($json) => $json->where('string', '2020-08-22')
                    ->etc()
                )
                ->has('person', fn ($json) => $json->where('id', 16)
                    ->where('surname', 'Sidorov')
                    ->where('oldSurname', null)
                    ->where('name', 'Maxim')
                    ->where('patronymic', 'Petrovich')
                )
                ->where('type', 2)
            )
            ->etc();

        $response = $this->getJson(route('dates.all'), $this->getHeaderUserToken());
        $response->assertStatus(200)->assertJson($expected);
    }

    public function test_all401(): void
    {
        $response = $this->getJson(route('dates.all'));
        $response->assertStatus(401);
    }

    #[DataProvider('upcoming200Provider')]
    public function test_upcoming200(string $request, callable $expected): void
    {
        $response = $this->getJson(route('dates.upcoming').$request, $this->getHeaderUserToken());
        $response->assertStatus(200)->assertJson($expected);
    }

    /**
     * @return array[]
     */
    public static function upcoming200Provider(): array
    {
        return [
            [
                '?date=2023-08-22&past_day=2&nearest_day=3',
                fn (AssertableJson $json) => $json->has('pastBirth', 1)
                    ->has('pastBirth.0', fn ($json) => $json
                        ->has('date', fn ($json) => $json->where('string', '1964-08-21')
                            ->etc()
                        )
                        ->has('person', fn ($json) => $json->where('id', 10)
                            ->where('surname', 'Solovyov')
                            ->where('oldSurname', null)
                            ->where('name', 'Igor')
                            ->where('patronymic', 'Ivanovich')
                        )
                        ->has('age', fn ($json) => $json->where('y', 59)
                            ->where('m', 0)
                            ->where('d', 4)
                            ->etc()
                        )
                    )
                    ->has('pastBirthWould', 1)
                    ->has('pastBirthWould.0', fn ($json) => $json
                        ->has('date', fn ($json) => $json->where('string', '1999-08-21')
                            ->etc()
                        )
                        ->has('person', fn ($json) => $json->where('id', 16)
                            ->where('surname', 'Sidorov')
                            ->where('oldSurname', null)
                            ->where('name', 'Maxim')
                            ->where('patronymic', 'Petrovich')
                        )
                        ->has('age', fn ($json) => $json->where('y', 24)
                            ->where('m', 0)
                            ->where('d', 4)
                            ->etc()
                        )
                    )
                    ->has('pastDeath', 1)
                    ->has('pastDeath.0', fn ($json) => $json
                        ->has('date', fn ($json) => $json->where('string', '2021-08-21')
                            ->etc()
                        )
                        ->has('person', fn ($json) => $json->where('id', 4)
                            ->where('surname', 'Danshina')
                            ->where('oldSurname.0', 'Pluta')
                            ->where('name', 'Tatyana')
                            ->where('patronymic', 'Ivanovna')
                        )
                        ->has('age', fn ($json) => $json->where('y', 68)
                            ->where('m', 11)
                            ->where('d', 4)
                            ->etc()
                        )
                        ->has('interval', fn ($json) => $json->where('y', 2)
                            ->where('m', 0)
                            ->where('d', 4)
                            ->etc()
                        )
                    )
                    ->has('todayBirth', 1)
                    ->has('todayBirth.0', fn ($json) => $json
                        ->has('date', fn ($json) => $json->where('string', '2012-08-22')
                            ->etc()
                        )
                        ->has('person', fn ($json) => $json->where('id', 12)
                            ->where('surname', 'Solovyov')
                            ->where('oldSurname', null)
                            ->where('name', 'Oleg')
                            ->where('patronymic', 'Igorevich')
                        )
                        ->has('age', fn ($json) => $json->where('y', 11)
                            ->where('m', 0)
                            ->where('d', 3)
                            ->etc()
                        )
                    )
                    ->has('todayBirthWould', 1)
                    ->has('todayBirthWould.0', fn ($json) => $json
                        ->has('date', fn ($json) => $json->where('string', '2000-08-22')
                            ->etc()
                        )
                        ->has('person', fn ($json) => $json->where('id', 17)
                            ->where('surname', 'Sidorov')
                            ->where('oldSurname', null)
                            ->where('name', 'Denis')
                            ->where('patronymic', 'Petrovich')
                        )
                        ->has('age', fn ($json) => $json->where('y', 23)
                            ->where('m', 0)
                            ->where('d', 3)
                            ->etc()
                        )
                    )
                    ->has('todayDeath', 1)
                    ->has('todayDeath.0', fn ($json) => $json
                        ->has('date', fn ($json) => $json->where('string', '2020-08-22')
                            ->etc()
                        )
                        ->has('person', fn ($json) => $json->where('id', 16)
                            ->where('surname', 'Sidorov')
                            ->where('oldSurname', null)
                            ->where('name', 'Maxim')
                            ->where('patronymic', 'Petrovich')
                        )
                        ->has('age', fn ($json) => $json->where('y', 21)
                            ->where('m', 0)
                            ->where('d', 1)
                            ->etc()
                        )
                        ->has('interval', fn ($json) => $json->where('y', 3)
                            ->where('m', 0)
                            ->where('d', 3)
                            ->etc()
                        )
                    )
                    ->has('nearestBirth', 2)
                    ->has('nearestBirth.0', fn ($json) => $json
                        ->has('date', fn ($json) => $json->where('string', '1982-08-23')
                            ->etc()
                        )
                        ->has('person', fn ($json) => $json->where('id', 14)
                            ->where('surname', 'Petrenko')
                            ->where('oldSurname', null)
                            ->where('name', 'Nina')
                            ->where('patronymic', 'Sergeevna')
                        )
                        ->has('age', fn ($json) => $json->where('y', 41)
                            ->where('m', 0)
                            ->where('d', 2)
                            ->etc()
                        )
                    )
                    ->has('nearestBirth.1', fn ($json) => $json
                        ->has('date', fn ($json) => $json->where('string', '2002-08-24')
                            ->etc()
                        )
                        ->has('person', fn ($json) => $json->where('id', 18)
                            ->where('surname', 'Sidorov')
                            ->where('oldSurname', null)
                            ->where('name', 'Igor')
                            ->where('patronymic', 'Petrovich')
                        )
                        ->has('age', fn ($json) => $json->where('y', 21)
                            ->where('m', 0)
                            ->where('d', 1)
                            ->etc()
                        )
                    )
                    ->has('nearestBirthWould', 0)
                    ->has('nearestDeath', 1)
                    ->has('nearestDeath.0', fn ($json) => $json
                        ->has('date', fn ($json) => $json->where('string', '2005-08-23')
                            ->etc()
                        )
                        ->has('person', fn ($json) => $json->where('id', 17)
                            ->where('surname', 'Sidorov')
                            ->where('oldSurname', null)
                            ->where('name', 'Denis')
                            ->where('patronymic', 'Petrovich')
                        )
                        ->has('age', fn ($json) => $json->where('y', 5)
                            ->where('m', 0)
                            ->where('d', 1)
                            ->etc()
                        )
                        ->has('interval', fn ($json) => $json->where('y', 18)
                            ->where('m', 0)
                            ->where('d', 2)
                            ->etc()
                        )
                    )
                    ->where('isEmpty', false),
            ],
        ];
    }

    public function test_upcoming401(): void
    {
        $response = $this->getJson(route('dates.upcoming'));
        $response->assertStatus(401);
    }

    #[DataProvider('upcoming422Provider')]
    public function test_upcoming422(string $request): void
    {
        $response = $this->getJson(route('dates.upcoming').$request, $this->getHeaderUserToken());
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function upcoming422Provider(): array
    {
        return [
            ['?date=&past_day=2&nearest_day=3'],
            ['?date=2000-01-&past_day=2&nearest_day=3'],
            ['?date=fake&past_day=2&nearest_day=3'],
            ['?date=2023-08-22&past_day=2.5&nearest_day=3'],
            ['?date=2023-08-22&past_day=fake&nearest_day=3'],
            ['?date=2023-08-22&past_day=2&nearest_day=3.5'],
            ['?date=2023-08-22&past_day=2&nearest_day=fake'],
            ['?date=2023-08-22&past_day=&nearest_day=3'],
            ['?date=2023-08-22&past_day=2&nearest_day='],
        ];
    }
}
