<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

final class GenderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_all200(): void
    {
        $expected = fn (AssertableJson $json) => $json->where(1, 'unknown')
            ->where(2, 'man')
            ->where(3, 'woman');

        $response = $this->getJson(route('gender.all'), $this->getHeaderAdminToken());
        $response->assertStatus(200)->assertJson($expected);
    }

    public function test_all401(): void
    {
        $response = $this->getJson(route('gender.all'));
        $response->assertStatus(401);
    }

    public function test_all403(): void
    {
        $response = $this->getJson(route('gender.all'), $this->getHeaderUserToken());
        $response->assertStatus(403);
    }
}
