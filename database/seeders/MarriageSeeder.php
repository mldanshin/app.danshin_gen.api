<?php

namespace Database\Seeders;

use App\Models\Eloquent\Marriage;
use Illuminate\Database\Seeder;

class MarriageSeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 1, 2, 3);
        $this->create(2, 3, 4, 3);
        $this->create(3, 5, 6, 3);
        $this->create(4, 9, 10, 3);
    }

    private function create(
        int $id,
        int $person1Id,
        int $person2Id,
        int $roleScopeId
    ): void {
        $obj = new Marriage;
        $obj->id = $id;
        $obj->person1_id = $person1Id;
        $obj->person2_id = $person2Id;
        $obj->role_scope_id = $roleScopeId;
        $obj->save();
    }
}
