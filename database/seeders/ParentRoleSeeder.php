<?php

namespace Database\Seeders;

use App\Models\Eloquent\ParentRole;
use Illuminate\Database\Seeder;

class ParentRoleSeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 'undefined');
        $this->create(2, 'father');
        $this->create(3, 'mother');
    }

    private function create(int $id, string $slug): void
    {
        $obj = new ParentRole;
        $obj->id = $id;
        $obj->slug = $slug;
        $obj->save();
    }
}
