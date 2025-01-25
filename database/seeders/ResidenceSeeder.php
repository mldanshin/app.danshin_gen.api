<?php

namespace Database\Seeders;

use App\Models\Eloquent\Residence;
use Illuminate\Database\Seeder;

class ResidenceSeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 1, 'Mosk', '1970-01-01');
        $this->create(2, 1, 'Kemerovo', '1986-01-01');
        $this->create(3, 2, 'Tomsk', null);
        $this->create(4, 3, 'Omsk', '');
    }

    private function create(
        int $id,
        int $personId,
        string $name,
        ?string $date
    ): void {
        $obj = new Residence;
        $obj->id = $id;
        $obj->person_id = $personId;
        $obj->name = $name;
        $obj->date = $date;
        $obj->save();
    }
}
