<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

final class PersonCreateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_create200(): void
    {
        $expected = fn (AssertableJson $json) => $json->where('isUnavailable', false)
            ->where('isLive', true)
            ->where('gender', 1)
            ->where('surname', '')
            ->where('name', '')
            ->where('oldSurname', [])
            ->where('patronymic', '')
            ->where('birthDate', null)
            ->where('birthPlace', '')
            ->where('deathDate', null)
            ->where('burialPlace', '')
            ->where('note', '')
            ->where('activities', [])
            ->where('emails', [])
            ->where('internet', [])
            ->where('phones', [])
            ->where('residences', [])
            ->where('parents', [])
            ->where('marriages', [])
            ->where('hasPatronymic', true);

        $response = $this->getJson(
            route('person.create'), $this->getHeaderAdminToken()
        );
        $response->assertStatus(200)->assertJson($expected);
    }

    public function test_create401(): void
    {
        $response = $this->getJson(route('person.create'));
        $response->assertStatus(401);
    }

    public function test_create403(): void
    {
        $response = $this->getJson(route('person.create'), $this->getHeaderUserToken());
        $response->assertStatus(403);
    }
}
