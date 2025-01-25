<?php

namespace Database\Seeders;

use App\Models\Eloquent\Phone;
use Illuminate\Database\Seeder;

class PhoneSeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 5, '9991112222');
        $this->create(2, 5, '9998882222');
        $this->create(3, 6, '9992222222');
        $this->create(4, 7, '9993332222');
        $this->create(5, 8, '9994442222');
        $this->create(6, 9, '9995552222');
        $this->create(7, 10, '9996662222');
        $this->create(8, 11, '9997772222');
        $this->create(9, 12, '9990002222');
        $this->create(10, 13, '8880002222');
        $this->create(11, 14, '8881112222');
        $this->create(12, 15, '8883332222');
        $this->create(13, 15, '8884442222');
        $this->create(14, 17, '8885552222');
        $this->create(15, 18, '8886662222');
        $this->create(16, 2, '999132244');
    }

    private function create(int $id, int $person, string $name): void
    {
        $phone = new Phone;
        $phone->id = $id;
        $phone->person_id = $person;
        $phone->name = $name;
        $phone->save();
    }
}
