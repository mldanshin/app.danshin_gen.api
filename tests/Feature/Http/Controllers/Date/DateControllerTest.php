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

        $response = $this->getJson(route('dates.all'), $this->getHeaderRelativeSsoToken());
        $response->assertStatus(200)->assertJson($expected);
    }

    public function test_all200ApiKey(): void
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

        $response = $this->getJson(route('dates.all'), $this->getHeaderAdminApiKey());
        $response->assertStatus(200)->assertJson($expected);
    }

    public function test_all200SortByDate(): void
    {
        $response = $this->getJson(
            route('dates.all', ['sort_by' => 'date']),
            $this->getHeaderRelativeSsoToken()
        );
        $response->assertStatus(200);
        
        $data = $response->json();
        $this->assertCount(21, $data);

        $dates = array_map(fn($item) => $item['date']['string'], $data);
        $sortedDates = $dates;
        sort($sortedDates);
        $this->assertEquals($sortedDates, $dates);
    }

    public function test_all200SortByAlphabet(): void
    {
        $response = $this->getJson(
            route('dates.all', ['sort_by' => 'alphabet']),
            $this->getHeaderRelativeSsoToken()
        );
        $response->assertStatus(200);
        
        $data = $response->json();
        $this->assertCount(21, $data);

        $expectedOrder = [
            ['surname' => 'Burkina',      'name' => 'Natalia',   'patronymic' => 'Vladimirovna', 'type' => 1],
            ['surname' => 'Danshin',      'name' => 'Egor',      'patronymic' => 'Leonidovich',  'type' => 1],
            ['surname' => 'Danshin',      'name' => 'Leonid',    'patronymic' => 'Pavlovich',    'type' => 1],
            ['surname' => 'Danshin',      'name' => 'Maxim',     'patronymic' => 'Leonidovich',  'type' => 1],
            ['surname' => 'Danshin',      'name' => 'Pavel',     'patronymic' => 'Tikhonovich',  'type' => 1],
            ['surname' => 'Danshin',      'name' => 'Pavel',     'patronymic' => 'Tikhonovich',  'type' => 2],
            ['surname' => 'Danshina',     'name' => 'Elizabeth', 'patronymic' => 'Dmitrievna',   'type' => 1],
            ['surname' => 'Danshina',     'name' => 'Elizabeth', 'patronymic' => 'Dmitrievna',   'type' => 2],
            ['surname' => 'Danshina',     'name' => 'Tatyana',   'patronymic' => 'Ivanovna',     'type' => 1],
            ['surname' => 'Danshina',     'name' => 'Tatyana',   'patronymic' => 'Ivanovna',     'type' => 2],
            ['surname' => 'Petrenko',     'name' => 'Nina',      'patronymic' => 'Sergeevna',    'type' => 1],
            ['surname' => 'Petrenko',     'name' => 'Olga',      'patronymic' => 'Sergeevna',    'type' => 1],
            ['surname' => 'Sidorov',      'name' => 'Denis',     'patronymic' => 'Petrovich',    'type' => 1],
            ['surname' => 'Sidorov',      'name' => 'Denis',     'patronymic' => 'Petrovich',    'type' => 2],
            ['surname' => 'Sidorov',      'name' => 'Igor',      'patronymic' => 'Petrovich',    'type' => 1],
            ['surname' => 'Sidorov',      'name' => 'Maxim',     'patronymic' => 'Petrovich',    'type' => 1],
            ['surname' => 'Sidorov',      'name' => 'Maxim',     'patronymic' => 'Petrovich',    'type' => 2],
            ['surname' => 'Solovyov',     'name' => 'Igor',      'patronymic' => 'Ivanovich',    'type' => 1],
            ['surname' => 'Solovyov',     'name' => 'Oleg',      'patronymic' => 'Igorevich',    'type' => 1],
            ['surname' => 'Solovyova',    'name' => 'Oksana',    'patronymic' => 'Leonidovna',   'type' => 1],
            ['surname' => 'Solovyova',    'name' => 'Olga',      'patronymic' => 'Igorevna',    'type' => 1],
        ];

        foreach ($expectedOrder as $index => $expectedPerson) {
            $actualPerson = $data[$index]['person'];
            $actualType = $data[$index]['type'];

            $this->assertEquals($expectedPerson['surname'], $actualPerson['surname'] ?? '', "Mismatch in surname at index {$index}");
            $this->assertEquals($expectedPerson['name'], $actualPerson['name'] ?? '', "Mismatch in name at index {$index}");
            $this->assertEquals($expectedPerson['patronymic'], $actualPerson['patronymic'] ?? '', "Mismatch in patronymic at index {$index}");
            $this->assertEquals($expectedPerson['type'], $actualType, "Mismatch in type at index {$index}");
        }
    }

    public function test_all200SortByAlphabetApiKey(): void
    {
        $response = $this->getJson(
            route('dates.all', ['sort_by' => 'alphabet']),
            $this->getHeaderAdminApiKey()
        );
        $response->assertStatus(200);
        
        $data = $response->json();
        $this->assertCount(21, $data);

        $expectedOrder = [
            ['surname' => 'Burkina',      'name' => 'Natalia',   'patronymic' => 'Vladimirovna', 'type' => 1],
            ['surname' => 'Danshin',      'name' => 'Egor',      'patronymic' => 'Leonidovich',  'type' => 1],
            ['surname' => 'Danshin',      'name' => 'Leonid',    'patronymic' => 'Pavlovich',    'type' => 1],
            ['surname' => 'Danshin',      'name' => 'Maxim',     'patronymic' => 'Leonidovich',  'type' => 1],
            ['surname' => 'Danshin',      'name' => 'Pavel',     'patronymic' => 'Tikhonovich',  'type' => 1],
            ['surname' => 'Danshin',      'name' => 'Pavel',     'patronymic' => 'Tikhonovich',  'type' => 2],
            ['surname' => 'Danshina',     'name' => 'Elizabeth', 'patronymic' => 'Dmitrievna',   'type' => 1],
            ['surname' => 'Danshina',     'name' => 'Elizabeth', 'patronymic' => 'Dmitrievna',   'type' => 2],
            ['surname' => 'Danshina',     'name' => 'Tatyana',   'patronymic' => 'Ivanovna',     'type' => 1],
            ['surname' => 'Danshina',     'name' => 'Tatyana',   'patronymic' => 'Ivanovna',     'type' => 2],
            ['surname' => 'Petrenko',     'name' => 'Nina',      'patronymic' => 'Sergeevna',    'type' => 1],
            ['surname' => 'Petrenko',     'name' => 'Olga',      'patronymic' => 'Sergeevna',    'type' => 1],
            ['surname' => 'Sidorov',      'name' => 'Denis',     'patronymic' => 'Petrovich',    'type' => 1],
            ['surname' => 'Sidorov',      'name' => 'Denis',     'patronymic' => 'Petrovich',    'type' => 2],
            ['surname' => 'Sidorov',      'name' => 'Igor',      'patronymic' => 'Petrovich',    'type' => 1],
            ['surname' => 'Sidorov',      'name' => 'Maxim',     'patronymic' => 'Petrovich',    'type' => 1],
            ['surname' => 'Sidorov',      'name' => 'Maxim',     'patronymic' => 'Petrovich',    'type' => 2],
            ['surname' => 'Solovyov',     'name' => 'Igor',      'patronymic' => 'Ivanovich',    'type' => 1],
            ['surname' => 'Solovyov',     'name' => 'Oleg',      'patronymic' => 'Igorevich',    'type' => 1],
            ['surname' => 'Solovyova',    'name' => 'Oksana',    'patronymic' => 'Leonidovna',   'type' => 1],
            ['surname' => 'Solovyova',    'name' => 'Olga',      'patronymic' => 'Igorevna',    'type' => 1],
        ];

        foreach ($expectedOrder as $index => $expectedPerson) {
            $actualPerson = $data[$index]['person'];
            $actualType = $data[$index]['type'];

            $this->assertEquals($expectedPerson['surname'], $actualPerson['surname'] ?? '', "Mismatch in surname at index {$index}");
            $this->assertEquals($expectedPerson['name'], $actualPerson['name'] ?? '', "Mismatch in name at index {$index}");
            $this->assertEquals($expectedPerson['patronymic'], $actualPerson['patronymic'] ?? '', "Mismatch in patronymic at index {$index}");
            $this->assertEquals($expectedPerson['type'], $actualType, "Mismatch in type at index {$index}");
        }
    }

    public function test_all200InvalidSortBy(): void
    {
        $response = $this->getJson(
            route('dates.all', ['sort_by' => 'invalid']),
            $this->getHeaderRelativeSsoToken()
        );
        $response->assertStatus(200);
        
        $data = $response->json();
        $this->assertCount(21, $data);
        
        $dates = array_map(fn($item) => $item['date']['string'], $data);
        $sortedDates = $dates;
        sort($sortedDates);
        $this->assertEquals($sortedDates, $dates);
    }

    public function test_all401(): void
    {
        $response = $this->getJson(route('dates.all'));
        $response->assertStatus(401);
    }

    public function test403(): void
    {
        $response = $this->getJson(route('dates.all'), $this->getHeaderUserSsoToken());
        $response->assertStatus(403);
    }

    public function test403ApiKey(): void
    {
        $response = $this->getJson(route('dates.all'), $this->getHeaderUserApiKey());
        $response->assertStatus(403);
    }

    #[DataProvider('upcoming200Provider')]
    public function test_upcoming200(string $request, callable $expected): void
    {
        $response = $this->getJson(route('dates.upcoming').$request, $this->getHeaderRelativeSsoToken());
        $response->assertStatus(200)->assertJson($expected);
    }

    #[DataProvider('upcoming200Provider')]
    public function test_upcoming200ApiKey(string $request, callable $expected): void
    {
        $response = $this->getJson(route('dates.upcoming').$request, $this->getHeaderAdminApiKey());
        $response->assertStatus(200)->assertJson($expected);
    }

    /**
     * @return array[]
     */
    public static function upcoming200Provider(): array
    {
        return [
            [
                '?date=2023-08-22&before_day=3&after_day=2',
                fn (AssertableJson $json) => $json->has('beforeBirth', 1)
                    ->has('beforeBirth.0', fn ($json) => $json
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
                    ->has('beforeBirthWould', 1)
                    ->has('beforeBirthWould.0', fn ($json) => $json
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
                    ->has('beforeDeath', 1)
                    ->has('beforeDeath.0', fn ($json) => $json
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
                    ->has('afterBirth', 2)
                    ->has('afterBirth.0', fn ($json) => $json
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
                    ->has('afterBirth.1', fn ($json) => $json
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
                    ->has('afterBirthWould', 0)
                    ->has('afterDeath', 1)
                    ->has('afterDeath.0', fn ($json) => $json
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

    public function testUpcoming403(): void
    {
        $response = $this->getJson(route('dates.upcoming'), $this->getHeaderUserSsoToken());
        $response->assertStatus(403);
    }

    public function testUpcoming403ApiKey(): void
    {
        $response = $this->getJson(route('dates.upcoming'), $this->getHeaderUserApiKey());
        $response->assertStatus(403);
    }

    #[DataProvider('upcoming422Provider')]
    public function test_upcoming422(string $request): void
    {
        $response = $this->getJson(route('dates.upcoming').$request, $this->getHeaderRelativeSsoToken());
        $response->assertStatus(422);
    }

    #[DataProvider('upcoming422Provider')]
    public function test_upcoming422ApiKey(string $request): void
    {
        $response = $this->getJson(route('dates.upcoming').$request, $this->getHeaderAdminApiKey());
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function upcoming422Provider(): array
    {
        return [
            ['?date=&before_day=2&after_day=3'],
            ['?date=2000-01-&before_day=2&after_day=3'],
            ['?date=fake&before_day=2&after_day=3'],
            ['?date=2023-08-22&before_day=2.5&after_day=3'],
            ['?date=2023-08-22&before_day=fake&after_day=3'],
            ['?date=2023-08-22&before_day=2&after_day=3.5'],
            ['?date=2023-08-22&before_day=2&after_day=fake'],
            ['?date=2023-08-22&before_day=&after_day=3'],
            ['?date=2023-08-22&before_day=2&after_day='],
        ];
    }
}
