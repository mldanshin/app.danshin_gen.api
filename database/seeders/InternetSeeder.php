<?php

namespace Database\Seeders;

use App\Models\Eloquent\Internet;
use Illuminate\Database\Seeder;

class InternetSeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 1, 'http://1.danshin.net', 'Internet1');
        $this->create(2, 1, 'http://2.danshin.net', 'Internet2');
    }

    private function create(
        int $id,
        int $personId,
        string $url,
        string $name
    ): void {
        $obj = new Internet;
        $obj->id = $id;
        $obj->person_id = $personId;
        $obj->url = $url;
        $obj->name = $name;
        $obj->save();
    }
}
