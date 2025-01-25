<?php

namespace Database\Seeders;

use App\Models\Eloquent\OldSurname;
use Illuminate\Database\Seeder;

class OldSurnameSeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 2, 'Petrova', 1);
        $this->create(2, 4, 'Pluta', 1);
        $this->create(3, 9, 'Danshin', 1);
        $this->create(4, 5, 'AFake', 2);
        $this->create(5, 5, 'Fake', 1);
    }

    private function create(
        int $id,
        int $personId,
        string $surname,
        int $order
    ): void {
        $obj = new OldSurname;
        $obj->id = $id;
        $obj->person_id = $personId;
        $obj->surname = $surname;
        $obj->order = $order;
        $obj->save();
    }
}
