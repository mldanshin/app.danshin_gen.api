<?php

namespace Database\Seeders;

use App\Models\Eloquent\Gender;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 'unknown');
        $this->create(2, 'man');
        $this->create(3, 'woman');
    }

    private function create(int $id, string $slug): void
    {
        $obj = new Gender;
        $obj->id = $id;
        $obj->slug = $slug;
        $obj->save();
    }
}
