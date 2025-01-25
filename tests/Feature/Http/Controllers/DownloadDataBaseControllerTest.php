<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

final class DownloadDataBaseControllerTest extends TestCase
{
    public function test200(): void
    {
        $this->markTestSkipped(
            'Тест пропущен так как тестирование идёт через sqlite в памяти, дамп которого не смог получить,
                а писать в рабочем коде получение дампа только из-за теста считаю лишним'
        );
    }

    public function test401(): void
    {
        $response = $this->getJson(route('download.data_base'));
        $response->assertStatus(401);
    }
}
