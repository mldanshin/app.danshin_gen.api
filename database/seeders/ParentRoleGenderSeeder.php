<?php

namespace Database\Seeders;

use App\Models\Eloquent\ParentRoleGender;
use Illuminate\Database\Seeder;

class ParentRoleGenderSeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 1);
        $this->create(1, 2);
        $this->create(1, 3);
        $this->create(2, 1);
        $this->create(2, 2);
        $this->create(3, 1);
        $this->create(3, 3);
    }

    private function create(int $gender_id, int $parent_id): void
    {
        $obj = new ParentRoleGender;
        $obj->gender_id = $gender_id;
        $obj->parent_id = $parent_id;
        $obj->save();
    }
}
