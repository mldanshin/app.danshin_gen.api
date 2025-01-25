<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Eloquent\Phone as PhoneEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PersonStoredPhoneControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('store200Provider')]
    public function test_store200(string $phone, string $expected): void
    {
        $response = $this->postJson(
            route('person.store'),
            [
                'is_unavailable' => true,
                'is_live' => false,
                'gender' => 2,
                'phones' => [
                    $phone,
                ],
            ],
            headers: $this->getHeaderAdminToken()
        );
        $response->assertStatus(200);
        $this->assertEquals(
            $expected,
            PhoneEloquentModel::where('person_id', $response['person_id'])->get()[0]->name
        );
    }

    /**
     * @return array[]
     */
    public static function store200Provider(): array
    {
        return [
            [
                '9090301122',
                '9090301122',
            ],
            [
                '+79090301122',
                '9090301122',
            ],
            [
                '79090301122',
                '9090301122',
            ],
            [
                '89090301122',
                '9090301122',
            ],
            [
                '+7 909 030 11 22',
                '9090301122',
            ],
            [
                '8 909 0301 122',
                '9090301122',
            ],
            [
                '8 909 03 01 122',
                '9090301122',
            ],
            [
                '8-909-030-11-22',
                '9090301122',
            ],
            [
                '3842 74-26-31',
                '3842742631',
            ],
            [
                '1234567890',
                '1234567890',
            ],
        ];
    }
}
