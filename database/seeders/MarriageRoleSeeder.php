<?php

namespace Database\Seeders;

use App\Models\Eloquent\MarriageRole;
use Illuminate\Database\Seeder;

class MarriageRoleSeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 'partner');
        $this->create(2, 'boyfriend');
        $this->create(3, 'girlfriend');
        $this->create(4, 'husband');
        $this->create(5, 'wife');
    }

    private function create(int $id, string $slug): void
    {
        $obj = new MarriageRole;
        $obj->id = $id;
        $obj->slug = $slug;
        $obj->save();
    }
}
