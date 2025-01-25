<?php

namespace Database\Seeders;

use App\Models\Eloquent\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 1, 'Teacher');
        $this->create(2, 2, 'Teacher');
        $this->create(3, 3, 'Radio technician');
        $this->create(4, 4, 'Educator');
    }

    private function create(
        int $id,
        int $personId,
        string $name
    ): void {
        $obj = new Activity;
        $obj->id = $id;
        $obj->person_id = $personId;
        $obj->name = $name;
        $obj->save();
    }
}
