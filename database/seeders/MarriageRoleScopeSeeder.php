<?php

namespace Database\Seeders;

use App\Models\Eloquent\MarriageRoleScope;
use Illuminate\Database\Seeder;

class MarriageRoleScopeSeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 2, 3);
        $this->create(2, 3, 2);
        $this->create(3, 4, 5);
        $this->create(4, 5, 4);
        $this->create(5, 1, 1);
    }

    private function create(int $id, int $role1_id, int $role2_id): void
    {
        $obj = new MarriageRoleScope;
        $obj->id = $id;
        $obj->role1_id = $role1_id;
        $obj->role2_id = $role2_id;
        $obj->save();
    }
}
