<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class TreeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('getModel200Provider')]
    public function test_get_model200(string $personId, string $request, callable $expected): void
    {
        $response = $this->getJson(route('tree.model', ['id' => $personId]).$request, $this->getHeaderUserToken());
        $response->assertStatus(200)->assertJson($expected);
    }

    /**
     * @return array[]
     */
    public static function getModel200Provider(): array
    {
        return [
            [
                '3',
                '?parent_id=1',
                'expected' => fn (AssertableJson $json) => $json->has('personTarget', fn ($json) => $json->where('id', 3)
                    ->where('surname', 'Danshin')
                    ->where('name', 'Leonid')
                    ->where('patronymic', 'Pavlovich')
                )
                    ->has('family', fn ($json) => $json->has('person', fn ($json) => $json->where('id', 1)
                    ->where('surname', 'Danshin')
                    ->where('oldSurname', null)
                    ->where('name', 'Pavel')
                    ->where('patronymic', 'Tikhonovich')
                    ->has('birthDate', fn ($json) => $json->where('string', '1905-10-11')
                        ->etc()
                    )
                    ->has('deathDate', fn ($json) => $json->where('string', '1986-02-04')
                    ->etc()
                    )
                    ->where('isPersonTarget', false)
                    )
                    ->has('marriage', 1)
                    ->has('marriage.0', fn ($json) => $json->where('id', 2)
                        ->where('surname', 'Danshina')
                        ->where('oldSurname.0', 'Petrova')
                        ->where('name', 'Elizabeth')
                        ->where('patronymic', 'Dmitrievna')
                        ->has('birthDate', fn ($json) => $json->where('string', '1909-09-04')
                            ->etc()
                        )
                        ->has('deathDate', fn ($json) => $json->where('string', '1995-12-14')
                            ->etc()
                        )
                        ->where('isPersonTarget', false)
                    )
                    ->has('children', 1)
                    ->has('children.0.person', fn ($json) => $json->where('id', 3)
                        ->where('surname', 'Danshin')
                        ->where('oldSurname', null)
                        ->where('name', 'Leonid')
                        ->where('patronymic', 'Pavlovich')
                        ->has('birthDate', fn ($json) => $json->where('string', '1950-01-23')
                            ->etc()
                        )
                        ->where('deathDate', null)
                        ->where('isPersonTarget', true)
                    )
                    ->has('children.0.marriage.0', fn ($json) => $json->where('id', 4)
                        ->where('surname', 'Danshina')
                        ->where('oldSurname.0', 'Pluta')
                        ->where('name', 'Tatyana')
                        ->where('patronymic', 'Ivanovna')
                        ->has('birthDate', fn ($json) => $json->where('string', '1952-09-17')
                            ->etc()
                        )
                        ->has('deathDate', fn ($json) => $json->where('string', '2021-08-21')
                            ->etc()
                        )
                        ->where('isPersonTarget', false)
                    )
                    ),
            ],
            [
                '3',
                '?parent_id=',
                'expected' => fn (AssertableJson $json) => $json->has('personTarget', fn ($json) => $json->where('id', 3)
                    ->where('surname', 'Danshin')
                    ->where('name', 'Leonid')
                    ->where('patronymic', 'Pavlovich')
                )
                    ->has('family', fn ($json) => $json->has('person', fn ($json) => $json->where('id', 1)
                    ->where('surname', 'Danshin')
                    ->where('oldSurname', null)
                    ->where('name', 'Pavel')
                    ->where('patronymic', 'Tikhonovich')
                    ->has('birthDate', fn ($json) => $json->where('string', '1905-10-11')
                        ->etc()
                    )
                    ->has('deathDate', fn ($json) => $json->where('string', '1986-02-04')
                    ->etc()
                    )
                    ->where('isPersonTarget', false)
                    )
                    ->has('marriage', 1)
                    ->has('marriage.0', fn ($json) => $json->where('id', 2)
                        ->where('surname', 'Danshina')
                        ->where('oldSurname.0', 'Petrova')
                        ->where('name', 'Elizabeth')
                        ->where('patronymic', 'Dmitrievna')
                        ->has('birthDate', fn ($json) => $json->where('string', '1909-09-04')
                            ->etc()
                        )
                        ->has('deathDate', fn ($json) => $json->where('string', '1995-12-14')
                            ->etc()
                        )
                        ->where('isPersonTarget', false)
                    )
                    ->has('children', 1)
                    ->has('children.0.person', fn ($json) => $json->where('id', 3)
                        ->where('surname', 'Danshin')
                        ->where('oldSurname', null)
                        ->where('name', 'Leonid')
                        ->where('patronymic', 'Pavlovich')
                        ->has('birthDate', fn ($json) => $json->where('string', '1950-01-23')
                            ->etc()
                        )
                        ->where('deathDate', null)
                        ->where('isPersonTarget', true)
                    )
                    ->has('children.0.marriage.0', fn ($json) => $json->where('id', 4)
                        ->where('surname', 'Danshina')
                        ->where('oldSurname.0', 'Pluta')
                        ->where('name', 'Tatyana')
                        ->where('patronymic', 'Ivanovna')
                        ->has('birthDate', fn ($json) => $json->where('string', '1952-09-17')
                            ->etc()
                        )
                        ->has('deathDate', fn ($json) => $json->where('string', '2021-08-21')
                            ->etc()
                        )
                        ->where('isPersonTarget', false)
                    )
                    ),
            ],
        ];
    }

    #[DataProvider('model422Provider')]
    public function test_model422(string $request): void
    {
        $response = $this->getJson(route('tree.model', ['id' => 1]).$request, $this->getHeaderUserToken());
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function model422Provider(): array
    {
        return [
            ['?parent_id=fake'],
            ['?parent_id=1.1'],
        ];
    }

    #[DataProvider('model404Provider')]
    public function test_model404(string $personId, string $request): void
    {
        $response = $this->get(route('tree.model', ['id' => $personId]).$request, $this->getHeaderUserToken());
        $response->assertStatus(404);
    }

    /**
     * @return array[]
     */
    public static function model404Provider(): array
    {
        return [
            ['1', '?parent_id=1'],
            ['90', '?parent_id=1'],
            ['5', '?parent_id=7'],
            ['fake', ''],
        ];
    }

    public function test_get_model401(): void
    {
        $response = $this->getJson(route('tree.model', ['id' => 1]));
        $response->assertStatus(401);
    }

    #[DataProvider('getImage200Provider')]
    public function test_get_image200(string $personId, string $request): void
    {
        $response = $this->getJson(route('tree.image', ['id' => $personId]).$request, $this->getHeaderUserToken());
        $response->assertStatus(200);
    }

    /**
     * @return array[]
     */
    public static function getImage200Provider(): array
    {
        return [
            [
                '3',
                '?parent_id=1',
            ],
            [
                '3',
                '?parent_id=',
            ],
            [
                '3',
                '',
            ],
        ];
    }

    #[DataProvider('image422Provider')]
    public function test_image422(string $request): void
    {
        $response = $this->getJson(route('tree.image', ['id' => 1]).$request, $this->getHeaderUserToken());
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function image422Provider(): array
    {
        return [
            ['?parent_id=fake'],
            ['?parent_id=1.1'],
        ];
    }

    #[DataProvider('image404Provider')]
    public function test_image404(string $personId, string $request): void
    {
        $response = $this->get(route('tree.image', ['id' => $personId]).$request, $this->getHeaderUserToken());
        $response->assertStatus(404);
    }

    /**
     * @return array[]
     */
    public static function image404Provider(): array
    {
        return [
            ['1', '?parent_id=1'],
            ['90', '?parent_id=1'],
            ['5', '?parent_id=7'],
            ['fake', ''],
        ];
    }

    public function test_get_image401(): void
    {
        $response = $this->getJson(route('tree.image', ['id' => 1]));
        $response->assertStatus(401);
    }

    #[DataProvider('getImageInteractive200Provider')]
    public function test_get_interactive_image200(string $personId, string $request): void
    {
        $response = $this->get(
            route('tree.image_interactive', ['id' => $personId]).$request, $this->getHeaderUserToken()
        );
        $response->assertStatus(200);
        $response->assertSee([
            'https://fake.fake/person/1',
            'https://fake.fake/tree/1',
            'https://fake.fake/image/person',
            'https://fake.fake/image/tree',
        ]);
    }

    /**
     * @return array[]
     */
    public static function getImageInteractive200Provider(): array
    {
        return [
            [
                '3',
                '?parent_id=1'
                    .'&path_person=https://fake.fake/person'
                    .'&path_tree=https://fake.fake/tree'
                    .'&image_person=https://fake.fake/image/person'
                    .'&image_tree=https://fake.fake/image/tree',
            ],
            [
                '3',
                '?parent_id='
                    .'&path_person=https://fake.fake/person'
                    .'&path_tree=https://fake.fake/tree'
                    .'&image_person=https://fake.fake/image/person'
                    .'&image_tree=https://fake.fake/image/tree',
            ],
            [
                '3',
                '?path_person=https://fake.fake/person'
                    .'&path_tree=https://fake.fake/tree'
                    .'&image_person=https://fake.fake/image/person'
                    .'&image_tree=https://fake.fake/image/tree',
            ],
        ];
    }

    #[DataProvider('imageInteractive422Provider')]
    public function test_image_interactive422(string $request): void
    {
        $response = $this->getJson(
            route('tree.image_interactive', ['id' => 3]).$request, $this->getHeaderUserToken()
        );
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function imageInteractive422Provider(): array
    {
        return [
            ['?parent_id=1'],
            [
                '?parent_id=fake'
                    .'&path_person=https://fake.fake/person'
                    .'&path_tree=https://fake.fake/tree'
                    .'&image_person=https://fake.fake/image/person'
                    .'&image_tree=https://fake.fake/image/tree',
            ],
            [
                '?parent_id=1.1'
                    .'&path_person=https://fake.fake/person'
                    .'&path_tree=https://fake.fake/tree'
                    .'&image_person=https://fake.fake/image/person'
                    .'&image_tree=https://fake.fake/image/tree',
            ],
        ];
    }

    #[DataProvider('imageInteractive404Provider')]
    public function test_image_interactive404(string $personId, string $request): void
    {
        $params = '&path_person=https://fake.fake/person&path_tree=https://fake.fake/tree&image_person=https://fake.fake/image/person&image_tree=https://fake.fake/image/tree';
        $response = $this->get(
            route('tree.image_interactive', ['id' => $personId]).$request.$params, $this->getHeaderUserToken()
        );
        $response->assertStatus(404);
    }

    /**
     * @return array[]
     */
    public static function imageInteractive404Provider(): array
    {
        return [
            ['1', '?parent_id=1'],
            ['90', '?parent_id=1'],
            ['5', '?parent_id=7'],
            ['fake', ''],
        ];
    }

    public function test_get_image_interactive401(): void
    {
        $response = $this->getJson(route('tree.image_interactive', ['id' => 1]));
        $response->assertStatus(401);
    }

    #[DataProvider('getToggle200Provider')]
    public function test_get_toggle200(string $personId, string $request, callable $expected): void
    {
        $response = $this->getJson(route('tree.toggle', ['id' => $personId]).$request, $this->getHeaderUserToken());
        $response->assertStatus(200)->assertJson($expected);
    }

    /**
     * @return array[]
     */
    public static function getToggle200Provider(): array
    {
        return [
            [
                '3',
                '?parent_id=1',
                'expected' => fn (AssertableJson $json) => $json->has('personTarget', fn ($json) => $json->where('id', 3)
                    ->where('surname', 'Danshin')
                    ->where('name', 'Leonid')
                    ->where('patronymic', 'Pavlovich')
                )
                    ->has('parentList', 2)
                    ->has('parentList.0', fn ($json) => $json->where('id', 1)
                    ->where('surname', 'Danshin')
                    ->where('name', 'Pavel')
                    ->where('patronymic', 'Tikhonovich')
                    )
                    ->has('parentList.1', fn ($json) => $json->where('id', 2)
                        ->where('surname', 'Danshina')
                        ->where('name', 'Elizabeth')
                        ->where('patronymic', 'Dmitrievna')
                    )
                    ->where('parentTarget', 1),
            ],
            [
                '3',
                '',
                'expected' => fn (AssertableJson $json) => $json->has('personTarget', fn ($json) => $json->where('id', 3)
                    ->where('surname', 'Danshin')
                    ->where('name', 'Leonid')
                    ->where('patronymic', 'Pavlovich')
                )
                    ->has('parentList', 2)
                    ->has('parentList.0', fn ($json) => $json->where('id', 1)
                    ->where('surname', 'Danshin')
                    ->where('name', 'Pavel')
                    ->where('patronymic', 'Tikhonovich')
                    )
                    ->has('parentList.1', fn ($json) => $json->where('id', 2)
                        ->where('surname', 'Danshina')
                        ->where('name', 'Elizabeth')
                        ->where('patronymic', 'Dmitrievna')
                    )
                    ->where('parentTarget', 1),
            ],
            [
                '1',
                '',
                'expected' => fn (AssertableJson $json) => $json->has('personTarget', fn ($json) => $json->where('id', 1)
                    ->where('surname', 'Danshin')
                    ->where('name', 'Pavel')
                    ->where('patronymic', 'Tikhonovich')
                )
                    ->has('parentList', 0)
                    ->where('parentTarget', null),
            ],
        ];
    }

    public function test_get_toggle401(): void
    {
        $response = $this->getJson(route('tree.toggle', ['id' => 1]));
        $response->assertStatus(401);
    }

    #[DataProvider('toggle404Provider')]
    public function test_toggle404(string $personId, string $request): void
    {
        $response = $this->get(route('tree.toggle', ['id' => $personId]).$request, $this->getHeaderUserToken());
        $response->assertStatus(404);
    }

    /**
     * @return array[]
     */
    public static function toggle404Provider(): array
    {
        return [
            ['1', '?parent_id=1'],
            ['90', '?parent_id=1'],
            ['5', '?parent_id=7'],
            ['fake', ''],
        ];
    }

    #[DataProvider('toggle422Provider')]
    public function test_toggle422(string $request): void
    {
        $response = $this->getJson(route('tree.toggle', ['id' => 1]).$request, $this->getHeaderUserToken());
        $response->assertStatus(422);
    }

    /**
     * @return array[]
     */
    public static function toggle422Provider(): array
    {
        return [
            ['?parent_id=fake'],
            ['?parent_id=1.1'],
        ];
    }
}
