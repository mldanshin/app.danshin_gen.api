<?php

namespace Database\Seeders;

use App\Models\Eloquent\Photo;
use Illuminate\Database\Seeder;

class PhotoSeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 1, '1.webp', null, 1);
        $this->create(2, 1, '2.webp', '1985-01-01', 2);
        $this->create(3, 1, '3.webp', '1985-??-01', 3);
        $this->create(4, 2, '3.webp', '????-01-01', 1);
    }

    private function create(
        int $id,
        int $personId,
        string $file,
        ?string $date,
        int $order
    ): void {
        $obj = new Photo;
        $obj->id = $id;
        $obj->person_id = $personId;
        $obj->file = $file;
        $obj->date = $date;
        $obj->order = $order;
        $obj->save();
    }
}
