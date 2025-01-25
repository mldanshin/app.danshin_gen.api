<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Eloquent\Activity as ActivityEloquentModel;
use App\Models\Eloquent\Email as EmailEloquentModel;
use App\Models\Eloquent\Internet as InternetEloquentModel;
use App\Models\Eloquent\Marriage as MarriageEloquentModel;
use App\Models\Eloquent\OldSurname as OldSurnameEloquentModel;
use App\Models\Eloquent\ParentChild as ParentChildEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Eloquent\Phone as PhoneEloquentModel;
use App\Models\Eloquent\Residence as ResidenceEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PersonUpdatedControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('update200Provider')]
    public function test_update200(array $request, callable $expected): void
    {
        $response = $this->putJson(
            route('person.update', ['person' => $request['id']]),
            $request,
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(200);
        $expected($this, $response['person_id']);
    }

    /**
     * @return array[]
     */
    public static function update200Provider(): array
    {
        return [
            [
                [
                    'id' => 10,
                    'is_unavailable' => true,
                    'is_live' => false,
                    'gender' => 2,
                    'surname' => 'FakeTestSurname',
                    'old_surname' => null,
                    'name' => 'FakeTestName',
                    'patronymic' => 'FakeTestPatronymic',
                    'birth_date' => '2023-??-??',
                    'birth_place' => null,
                    'death_date' => null,
                    'burial_place' => null,
                    'note' => null,
                    'activities' => null,
                    'emails' => null,
                    'internet' => null,
                    'phones' => null,
                    'residences' => null,
                    'parents' => null,
                    'marriages' => null,
                ],
                function (PersonUpdatedControllerTest $object, int $personId) {
                    $person = PeopleEloquentModel::find($personId);
                    $object->assertEquals(true, $person->is_unavailable);
                    $object->assertEquals(2, $person->gender_id);
                    $object->assertEquals('FakeTestSurname', $person->surname);
                    $object->assertCount(
                        0,
                        OldSurnameEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertEquals('FakeTestName', $person->name);
                    $object->assertEquals('FakeTestPatronymic', $person->patronymic);
                    $object->assertEquals('2023-??-??', $person->birth_date);
                    $object->assertEquals(null, $person->birth_place);
                    $object->assertEquals(null, $person->death_date);
                    $object->assertEquals(null, $person->burial_place);
                    $object->assertEquals(null, $person->note);
                    $object->assertCount(
                        0,
                        ActivityEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertCount(
                        0,
                        EmailEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertCount(
                        0,
                        InternetEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertCount(
                        0,
                        PhoneEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertCount(
                        0,
                        ResidenceEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertCount(
                        0,
                        ParentChildEloquentModel::where('child_id', $personId)->get()
                    );
                    $object->assertCount(
                        0,
                        MarriageEloquentModel::where('child_id', $personId)->get()
                    );
                },
            ],
            [
                [
                    'id' => 8,
                    'is_unavailable' => false,
                    'is_live' => true,
                    'gender' => 1,
                    'surname' => '',
                    'old_surname' => [
                        [
                            'surname' => 'FakeOldSurname',
                            'order' => 1,
                        ],
                    ],
                    'name' => '',
                    'has_patronymic' => false,
                    'birth_date' => '',
                    'birth_place' => '',
                    'death_date' => '',
                    'burial_place' => '',
                    'note' => '',
                    'activities' => [],
                    'emails' => [],
                    'internet' => [],
                    'phones' => [],
                    'residences' => [],
                    'parents' => [],
                    'marriages' => [],
                ],
                function (PersonUpdatedControllerTest $object, int $personId) {
                    $person = PeopleEloquentModel::find($personId);
                    $object->assertEquals(false, $person->is_unavailable);
                    $object->assertEquals(1, $person->gender_id);
                    $object->assertEquals(null, $person->surname);
                    $object->assertEquals(
                        'FakeOldSurname',
                        OldSurnameEloquentModel::where('person_id', $personId)->get()[0]->surname
                    );
                    $object->assertEquals(null, $person->name);
                    $object->assertTrue($person->patronymic === '');
                    $object->assertEquals(null, $person->birth_date);
                    $object->assertEquals(null, $person->birth_place);
                    $object->assertEquals(null, $person->death_date);
                    $object->assertEquals(null, $person->burial_place);
                    $object->assertEquals(null, $person->note);
                    $object->assertCount(
                        0,
                        ActivityEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertCount(
                        0,
                        EmailEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertCount(
                        0,
                        InternetEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertCount(
                        0,
                        PhoneEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertCount(
                        0,
                        ResidenceEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertCount(
                        0,
                        ParentChildEloquentModel::where('child_id', $personId)->get()
                    );
                    $object->assertCount(
                        0,
                        MarriageEloquentModel::where('child_id', $personId)->get()
                    );
                },
            ],
            [
                [
                    'id' => 5,
                    'is_unavailable' => false,
                    'is_live' => false,
                    'gender' => 2,
                    'surname' => null,
                    'old_surname' => [
                        [
                            'surname' => 'FakeOldSurname1',
                            'order' => 1,
                        ],
                        [
                            'surname' => 'FakeOldSurname2',
                            'order' => 2,
                        ],
                    ],
                    'name' => null,
                    'patronymic' => null,
                    'has_patronymic' => true,
                    'birth_date' => null,
                    'birth_place' => 'FakeBirthPlace',
                    'death_date' => '2000-01-12',
                    'burial_place' => 'FakeCity',
                    'note' => 'FakeNote',
                    'activities' => [
                        'FakeActivitie1',
                        'FakeActivitie2',
                    ],
                    'emails' => [
                        'fakeEmail1@fake.com',
                        'fakeEmail2@fake.com',
                    ],
                    'internet' => [
                        [
                            'url' => 'http://fakeUrl1.com',
                            'name' => 'FakeName1',
                        ],
                        [
                            'url' => 'http://fakeUrl2.com',
                            'name' => 'FakeName2',
                        ],
                    ],
                    'phones' => [
                        '89996660011',
                        '89996660022',
                    ],
                    'residences' => [
                        [
                            'name' => 'FakeResidence1',
                            'date' => null,
                        ],
                        [
                            'name' => 'FakeResidence2',
                            'date' => '',
                        ],
                        [
                            'name' => 'FakeResidence3',
                            'date' => '????-10-01',
                        ],
                        [
                            'name' => 'FakeResidence4',
                            'date' => '2020-08-11',
                        ],
                    ],
                    'parents' => [
                        [
                            'person' => '1',
                            'role' => '1',
                        ],
                        [
                            'person' => '2',
                            'role' => '3',
                        ],
                    ],
                    'marriages' => [
                        [
                            'role' => '2',
                            'soulmate' => '4',
                            'soulmate_role' => '3',
                        ],
                    ],
                ],
                function (PersonUpdatedControllerTest $object, int $personId) {
                    $person = PeopleEloquentModel::find($personId);
                    $object->assertEquals(false, $person->is_unavailable);
                    $object->assertEquals(2, $person->gender_id);
                    $object->assertEquals(null, $person->surname);
                    $object->assertEquals(
                        'FakeOldSurname1',
                        OldSurnameEloquentModel::where('person_id', $personId)->get()[0]->surname
                    );
                    $object->assertEquals(
                        'FakeOldSurname2',
                        OldSurnameEloquentModel::where('person_id', $personId)->get()[1]->surname
                    );
                    $object->assertEquals(null, $person->name);
                    $object->assertEquals(null, $person->patronymic);
                    $object->assertEquals(null, $person->birth_date);
                    $object->assertEquals('FakeBirthPlace', $person->birth_place);
                    $object->assertEquals('2000-01-12', $person->death_date);
                    $object->assertEquals('FakeCity', $person->burial_place);
                    $object->assertEquals('FakeNote', $person->note);
                    $object->assertCount(
                        2,
                        ActivityEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertEquals(
                        'FakeActivitie1',
                        ActivityEloquentModel::where('person_id', $personId)->get()[0]->name
                    );
                    $object->assertEquals(
                        'FakeActivitie2',
                        ActivityEloquentModel::where('person_id', $personId)->get()[1]->name
                    );
                    $object->assertCount(
                        2,
                        EmailEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertEquals(
                        'fakeEmail1@fake.com',
                        EmailEloquentModel::where('person_id', $personId)->get()[0]->name
                    );
                    $object->assertEquals(
                        'fakeEmail2@fake.com',
                        EmailEloquentModel::where('person_id', $personId)->get()[1]->name
                    );
                    $object->assertCount(
                        2,
                        InternetEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertEquals(
                        'http://fakeUrl1.com',
                        InternetEloquentModel::where('person_id', $personId)->get()[0]->url
                    );
                    $object->assertEquals(
                        'FakeName1',
                        InternetEloquentModel::where('person_id', $personId)->get()[0]->name
                    );
                    $object->assertEquals(
                        'http://fakeUrl2.com',
                        InternetEloquentModel::where('person_id', $personId)->get()[1]->url
                    );
                    $object->assertEquals(
                        'FakeName2',
                        InternetEloquentModel::where('person_id', $personId)->get()[1]->name
                    );
                    $object->assertCount(
                        2,
                        PhoneEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertEquals(
                        '9996660011',
                        PhoneEloquentModel::where('person_id', $personId)->get()[0]->name
                    );
                    $object->assertEquals(
                        '9996660022',
                        PhoneEloquentModel::where('person_id', $personId)->get()[1]->name
                    );
                    $object->assertCount(
                        4,
                        ResidenceEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertEquals(
                        'FakeResidence1',
                        ResidenceEloquentModel::where('person_id', $personId)->get()[0]->name
                    );
                    $object->assertEquals(
                        null,
                        ResidenceEloquentModel::where('person_id', $personId)->get()[0]->date
                    );
                    $object->assertEquals(
                        'FakeResidence2',
                        ResidenceEloquentModel::where('person_id', $personId)->get()[1]->name
                    );
                    $object->assertEquals(
                        null,
                        ResidenceEloquentModel::where('person_id', $personId)->get()[1]->date
                    );
                    $object->assertEquals(
                        'FakeResidence3',
                        ResidenceEloquentModel::where('person_id', $personId)->get()[2]->name
                    );
                    $object->assertEquals(
                        '????-10-01',
                        ResidenceEloquentModel::where('person_id', $personId)->get()[2]->date
                    );
                    $object->assertEquals(
                        'FakeResidence4',
                        ResidenceEloquentModel::where('person_id', $personId)->get()[3]->name
                    );
                    $object->assertEquals(
                        '2020-08-11',
                        ResidenceEloquentModel::where('person_id', $personId)->get()[3]->date
                    );
                    $object->assertCount(
                        2,
                        ParentChildEloquentModel::where('child_id', $personId)->get()
                    );
                    $object->assertEquals(
                        1,
                        ParentChildEloquentModel::where('child_id', $personId)->get()[0]->parent_id
                    );
                    $object->assertEquals(
                        1,
                        ParentChildEloquentModel::where('child_id', $personId)->get()[0]->parent_role_id
                    );
                    $object->assertEquals(
                        2,
                        ParentChildEloquentModel::where('child_id', $personId)->get()[1]->parent_id
                    );
                    $object->assertEquals(
                        3,
                        ParentChildEloquentModel::where('child_id', $personId)->get()[1]->parent_role_id
                    );
                    $object->assertCount(
                        1,
                        MarriageEloquentModel::where('person1_id', $personId)->get()
                    );
                    $object->assertEquals(
                        4,
                        MarriageEloquentModel::where('person1_id', $personId)->get()[0]->person2_id
                    );
                    $object->assertEquals(
                        1,
                        MarriageEloquentModel::where('person1_id', $personId)->get()[0]->role_scope_id
                    );
                },
            ],
            [
                [
                    'id' => 5,
                    'is_unavailable' => false,
                    'is_live' => true,
                    'gender' => 1,
                    'surname' => '',
                    'old_surname' => [
                        [
                            'surname' => 'FakeOldSurname',
                            'order' => 1,
                        ],
                    ],
                    'name' => '',
                    'has_patronymic' => false,
                    'birth_date' => '',
                    'birth_place' => '',
                    'death_date' => '',
                    'burial_place' => '',
                    'note' => '',
                    'activities' => [],
                    'emails' => [
                        'mail@danshin.net',
                    ],
                    'internet' => [],
                    'phones' => [
                        '9991112222',
                    ],
                    'residences' => [],
                    'parents' => [],
                    'marriages' => [],
                ],
                function (PersonUpdatedControllerTest $object, int $personId) {
                    $person = PeopleEloquentModel::find($personId);
                    $object->assertEquals(false, $person->is_unavailable);
                    $object->assertEquals(1, $person->gender_id);
                    $object->assertEquals(null, $person->surname);
                    $object->assertEquals(
                        'FakeOldSurname',
                        OldSurnameEloquentModel::where('person_id', $personId)->get()[0]->surname
                    );
                    $object->assertEquals(null, $person->name);
                    $object->assertTrue($person->patronymic === '');
                    $object->assertEquals(null, $person->birth_date);
                    $object->assertEquals(null, $person->birth_place);
                    $object->assertEquals(null, $person->death_date);
                    $object->assertEquals(null, $person->burial_place);
                    $object->assertEquals(null, $person->note);
                    $object->assertCount(
                        0,
                        ActivityEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertCount(
                        1,
                        EmailEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertEquals(
                        'mail@danshin.net',
                        EmailEloquentModel::where('person_id', $personId)->get()[0]->name
                    );
                    $object->assertCount(
                        0,
                        InternetEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertCount(
                        1,
                        PhoneEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertEquals(
                        '9991112222',
                        PhoneEloquentModel::where('person_id', $personId)->get()[0]->name
                    );
                    $object->assertCount(
                        0,
                        ResidenceEloquentModel::where('person_id', $personId)->get()
                    );
                    $object->assertCount(
                        0,
                        ParentChildEloquentModel::where('child_id', $personId)->get()
                    );
                    $object->assertCount(
                        0,
                        MarriageEloquentModel::where('child_id', $personId)->get()
                    );
                },
            ],
        ];
    }

    public function test_update401(): void
    {
        $response = $this->putJson(route('person.update', ['person' => 1]));
        $response->assertStatus(401);
    }

    public function test_update403(): void
    {
        $response = $this->putJson(
            route('person.update', ['person' => 1]),
            headers: $this->getHeaderUserToken()
        );
        $response->assertStatus(403);
    }
}
