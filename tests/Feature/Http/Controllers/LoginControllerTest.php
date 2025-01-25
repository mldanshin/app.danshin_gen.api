<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

final class LoginControllerTest extends TestCase
{
    public function test_login(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(401);
    }
}
