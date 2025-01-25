<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\DateTimeCustom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PersonStored422ControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('isUnavailableProvider')]
    public function test_is_unavailable(array $request): void
    {
        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function isUnavailableProvider(): array
    {
        return [
            [
                [
                    'is_live' => false,
                    'gender' => 1,
                ],
            ],
            [
                [
                    'is_unavailable' => 'fake',
                    'is_live' => false,
                    'gender' => 1,
                ],
            ],
            [
                [
                    'is_unavailable' => 2,
                    'is_live' => false,
                    'gender' => 1,
                ],
            ],
            [
                [
                    'is_unavailable' => [],
                    'is_live' => false,
                    'gender' => 1,
                ],
            ],
        ];
    }

    #[DataProvider('isLiveProvider')]
    public function test_is_live(array $request): void
    {
        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function isLiveProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'gender' => 1,
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => 'fake',
                    'gender' => 1,
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => 2,
                    'gender' => 1,
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => [],
                    'gender' => 1,
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => true,
                    'gender' => 1,
                    'death_date' => '2020-01-01',
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => true,
                    'gender' => 1,
                    'death_date' => '????-01-01',
                ],
            ],
        ];
    }

    #[DataProvider('genderProvider')]
    public function test_gender(array $request): void
    {
        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function genderProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 'fake',
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 99,
                ],
            ],
        ];
    }

    #[DataProvider('surnameProvider')]
    public function test_surname(array $request): void
    {
        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function surnameProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'surname' => str_repeat('12345', 51).'1', // 256 chars
                ],
            ],
        ];
    }

    #[DataProvider('oldSurnameProvider')]
    public function test_old_surname(array $request): void
    {
        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function oldSurnameProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'old_surname' => [
                        [
                            'surname' => str_repeat('12345', 51).'1', // 256 chars
                            'order' => null,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'old_surname' => [
                        [
                            'surname' => '',
                            'order' => null,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'old_surname' => [
                        [
                            'surname' => 'Fake',
                            'order' => null,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'old_surname' => [
                        [
                            'surname' => null,
                            'order' => 1,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'old_surname' => [
                        [
                            'surname' => 'Fake',
                            'order' => 0,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'old_surname' => [
                        [
                            'surname' => 'Fake',
                            'order' => 1,
                        ],
                        [
                            'surname' => 'Fake',
                            'order' => 1,
                        ],
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('nameProvider')]
    public function test_name(array $request): void
    {
        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function nameProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'name' => str_repeat('12345', 51).'1', // 256 chars
                ],
            ],
        ];
    }

    #[DataProvider('patronymicProvider')]
    public function test_patronymic(array $request): void
    {
        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function patronymicProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'patronymic' => str_repeat('12345', 51).'1', // 256 chars
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'patronymic' => 'Fake',
                    'has_patronymic' => false,
                ],
            ],
        ];
    }

    #[DataProvider('birthDateProvider')]
    public function test_birth_date(array $request): void
    {
        DateTimeCustom::setMockNow('2019-08-21');

        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function birthDateProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'birth_date' => '2020-02-04',
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'birth_date' => '02-04',
                ],
            ],
        ];
    }

    #[DataProvider('birthPlaceProvider')]
    public function test_birth_place(array $request): void
    {
        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function birthPlaceProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'birth_place' => str_repeat('12345', 51).'1', // 256 chars
                ],
            ],
        ];
    }

    #[DataProvider('deathDateProvider')]
    public function test_death_date(array $request): void
    {
        DateTimeCustom::setMockNow('2019-08-21');

        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function deathDateProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'death_date' => '2020-02-04',
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'birth_date' => '2018-02-04',
                    'death_date' => '2017-08-11',
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'death_date' => '02-04',
                ],
            ],
        ];
    }

    #[DataProvider('burialPlaceProvider')]
    public function test_burial_place(array $request): void
    {
        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function burialPlaceProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'burial_place' => str_repeat('12345', 51).'1', // 256 chars
                ],
            ],
        ];
    }

    #[DataProvider('activitiesProvider')]
    public function test_activities(array $request): void
    {
        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function activitiesProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'activities' => [
                        str_repeat('12345', 51).'1', // 256 chars
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'activities' => [
                        '',
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'activities' => [
                        'fake',
                        'fake',
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('emailsProvider')]
    public function test_emails(array $request): void
    {
        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function emailsProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'emails' => [
                        str_repeat('12345', 51).'@fake.com', // 256 chars
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'emails' => [
                        'fake',
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'emails' => [
                        '',
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'emails' => [
                        'fake@fake.ru',
                        'fake@fake.ru',
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'emails' => [
                        'mail@danshin.net',
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('internetProvider')]
    public function test_internet(array $request): void
    {
        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function internetProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'internet' => [
                        [
                            'name' => str_repeat('12345', 51).'@fake.com', // 256 chars
                            'url' => 'http://fake.com',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'internet' => [
                        [
                            'name' => '',
                            'url' => 'http://fake.com',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'internet' => [
                        [
                            'name' => null,
                            'url' => 'http://fake.com',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'internet' => [
                        [
                            'name' => 'fake',
                            'url' => 'http://fake1.com',
                        ],
                        [
                            'name' => 'fake',
                            'url' => 'http://fake2.com',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'internet' => [
                        [
                            'name' => 'fakeName',
                            'url' => 'https://'.str_repeat('12345', 51).'fake.com', // 256 chars
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'internet' => [
                        [
                            'name' => 'fakeName',
                            'url' => '',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'internet' => [
                        [
                            'name' => 'fakeName',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'internet' => [
                        [
                            'name' => 'fakeName',
                            'url' => 'fake.com',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'internet' => [
                        [
                            'name' => 'fakeName1',
                            'url' => 'https://fake.com',
                        ],
                        [
                            'name' => 'fakeName2',
                            'url' => 'https://fake.com',
                        ],
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('phonesProvider')]
    public function test_phones(array $request): void
    {
        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function phonesProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'phones' => [
                        '',
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'phones' => [
                        '9091231122',
                        '9091231122',
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'phones' => [
                        '9991112222',
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'phones' => [
                        '123456789',
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'phones' => [
                        'fake123456',
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('residencesProvider')]
    public function test_residences(array $request): void
    {
        DateTimeCustom::setMockNow('2019-08-21');

        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function residencesProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'residences' => [
                        [
                            'name' => str_repeat('12345', 51).'1', // 256 chars
                            'date' => '2018-12-01',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'residences' => [
                        [
                            '',
                            'date' => '2018-12-01',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'residences' => [
                        [
                            'date' => '2018-12-01',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'residences' => [
                        [
                            'name' => 'Fake',
                            'date' => '12-01',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'residences' => [
                        [
                            'name' => 'Fake',
                            'date' => '2023-12-01',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'residences' => [
                        [
                            'name' => 'Fake1',
                            'date' => '2023-12-01',
                        ],
                        [
                            'name' => 'Fake2',
                            'date' => '2023-12-01',
                        ],
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('parentsProvider')]
    public function test_parents(array $request): void
    {
        DateTimeCustom::setMockNow('2019-08-21');

        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function parentsProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'parents' => [
                        [
                            'person' => null,
                            'role' => 1,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'parents' => [
                        [
                            'person' => 'fake',
                            'role' => 1,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'parents' => [
                        [
                            'person' => 1,
                            'role' => 2,
                        ],
                        [
                            'person' => 1,
                            'role' => 1,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'parents' => [
                        [
                            'person' => 99,
                            'role' => 1,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'parents' => [
                        [
                            'person' => 1,
                            'role' => 1,
                        ],
                    ],
                    'marriages' => [
                        [
                            'role' => 1,
                            'soulmate' => 1,
                            'soulmate_role' => 2,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'parents' => [
                        [
                            'person' => 2,
                            'role' => null,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'parents' => [
                        [
                            'person' => 1,
                            'role' => 'fake',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'parents' => [
                        [
                            'person' => 1,
                            'role' => 99,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'birth_date' => '1888-09-01',
                    'parents' => [
                        [
                            'person' => 1,
                            'role' => 1,
                        ],
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('marriagesProvider')]
    public function test_marriages(array $request): void
    {
        DateTimeCustom::setMockNow('2019-08-21');

        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function marriagesProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'marriages' => [
                        'fake',
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'marriages' => [
                        [
                            'soulmate' => 2,
                            'soulmate_role' => 3,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'marriages' => [
                        [
                            'role' => 'fake',
                            'soulmate' => 2,
                            'soulmate_role' => 3,
                        ],
                    ],
                ],
            ],
            [//
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'marriages' => [
                        [
                            'role' => '99',
                            'soulmate' => 2,
                            'soulmate_role' => 3,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'marriages' => [
                        [
                            'role' => 1,
                            'soulmate_role' => 3,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'marriages' => [
                        [
                            'role' => 1,
                            'soulmate' => 'fake',
                            'soulmate_role' => 3,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'marriages' => [
                        [
                            'role' => 1,
                            'soulmate' => 99,
                            'soulmate_role' => 3,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'parents' => [
                        [
                            'person' => 2,
                        ],
                    ],
                    'marriages' => [
                        [
                            'role' => 1,
                            'soulmate' => 2,
                            'soulmate_role' => 3,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'marriages' => [
                        [
                            'role' => 1,
                            'soulmate' => 2,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'marriages' => [
                        [
                            'role' => 1,
                            'soulmate' => 2,
                            'soulmate_role' => 'fake',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'marriages' => [
                        [
                            'role' => 1,
                            'soulmate' => 2,
                            'soulmate_role' => 99,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => true,
                    'birth_date' => '2018-01-01',
                    'gender' => 1,
                    'marriages' => [
                        [
                            'role' => 1,
                            'soulmate' => 2,
                            'soulmate_role' => 1,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => true,
                    'gender' => 1,
                    'marriages' => [
                        [
                            'role' => 2,
                            'soulmate' => 2,
                            'soulmate_role' => 2,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => true,
                    'gender' => 2,
                    'marriages' => [
                        [
                            'role' => 3,
                            'soulmate' => 2,
                            'soulmate_role' => 2,
                        ],
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('photoProvider')]
    public function test_photo(array $request): void
    {
        DateTimeCustom::setMockNow('2022-08-21');

        $response = $this->postJson(
            route('person.store'),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(422);

        DateTimeCustom::resetMockNow();
    }

    /**
     * @return array[]
     */
    public static function photoProvider(): array
    {
        return [
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'photo' => [
                        [
                            'order' => null,
                            'file_name' => 'temp.png',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'photo' => [
                        [
                            'order' => 'fake',
                            'file_name' => 'temp.png',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'photo' => [
                        [
                            'order' => 0,
                            'file_name' => 'temp.png',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'photo' => [
                        [
                            'order' => 1,
                            'file_name' => 'temp.png',
                        ],
                        [
                            'order' => 1,
                            'file_name' => 'fake1.png',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'photo' => [
                        [
                            'order' => 1,
                            'date' => '2222',
                            'file_name' => 'temp.png',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'photo' => [
                        [
                            'order' => 1,
                            'date' => '2222-10-01',
                            'file_name' => 'temp.png',
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'photo' => [
                        [
                            'order' => 1,
                            'file_name' => null,
                        ],
                    ],
                ],
            ],
            [
                [
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'photo' => [
                        [
                            'order' => 1,
                            'file_name' => 'fake.jpg',
                        ],
                    ],
                ],
            ],
            [
                [
                    'id' => 1,
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 1,
                    'photo' => [
                        [
                            'order' => 1,
                            'file_name' => 'temp.png',
                        ],
                        [
                            'order' => 2,
                            'file_name' => 'temp.png',
                        ],
                    ],
                ],
            ],
        ];
    }
}
