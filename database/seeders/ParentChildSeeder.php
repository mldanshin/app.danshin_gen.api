<?php

namespace Database\Seeders;

use App\Models\Eloquent\ParentChild;
use Illuminate\Database\Seeder;

class ParentChildSeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 1, 3, 2);
        $this->create(2, 2, 3, 3);
        $this->create(3, 3, 5, 2);
        $this->create(4, 3, 8, 2);
        $this->create(5, 3, 9, 2);
        $this->create(6, 4, 5, 3);
        $this->create(7, 4, 8, 3);
        $this->create(8, 4, 9, 3);
        $this->create(9, 5, 7, 2);
        $this->create(10, 6, 7, 3);
        $this->create(11, 9, 11, 3);
        $this->create(12, 9, 12, 3);
        $this->create(13, 10, 11, 2);
        $this->create(14, 10, 12, 2);
    }

    private function create(
        int $id,
        int $parentId,
        int $childId,
        int $parentRoleId
    ): void {
        $obj = new ParentChild;
        $obj->id = $id;
        $obj->parent_id = $parentId;
        $obj->child_id = $childId;
        $obj->parent_role_id = $parentRoleId;
        $obj->save();
    }
}
