<?php

namespace Database\Seeders;

use App\Models\Eloquent\MarriageRoleGender;
use Illuminate\Database\Seeder;

class MarriageRoleGenderSeeder extends Seeder
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
        $this->create(4, 1);
        $this->create(4, 2);
        $this->create(5, 1);
        $this->create(5, 3);
    }

    private function create(int $roleId, int $genderId): void
    {
        $obj = new MarriageRoleGender;
        $obj->role_id = $roleId;
        $obj->gender_id = $genderId;
        $obj->save();
    }
}
