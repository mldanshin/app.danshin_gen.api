<?php

namespace Database\Seeders;

use App\Models\Eloquent\Email;
use Illuminate\Database\Seeder;

class EmailSeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 5, 'mail@danshin.net');
        $this->create(2, 6, 'natali@fakemail.ru');
        $this->create(3, 7, 'den@fakemail.ru');
        $this->create(4, 9, 'oks@fakemail.ru');
        $this->create(5, 10, 'bilet@fakemail.ru');
        $this->create(6, 11, 'bulbul@fakemail.ru');
        $this->create(7, 12, 'oleg@fakemail.ru');
        $this->create(8, 16, 'max@fakemail.ru');
        $this->create(9, 18, 'igor@fakemail.ru');
        $this->create(10, 2, 'ff@fakemail.ru');
    }

    private function create(int $id, int $person, string $name): void
    {
        $email = new Email;
        $email->id = $id;
        $email->person_id = $person;
        $email->name = $name;
        $email->save();
    }
}
