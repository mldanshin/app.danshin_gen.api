<?php

namespace Database\Seeders;

use App\Models\Eloquent\People;
use Illuminate\Database\Seeder;

class PeopleSeeder extends Seeder
{
    public function run(): void
    {
        $this->create(1, 1, 2, 'Danshin', 'Pavel', 'Tikhonovich', '1905-10-11', 'Novosibirsk', '1986-02-04', 'Kemerovo', null);
        $this->create(2, 0, 3, 'Danshina', 'Elizabeth', 'Dmitrievna', '1909-09-04', 'Novosibirsk', '1995-12-14', 'Kemerovo', null);
        $this->create(3, 0, 2, 'Danshin', 'Leonid', 'Pavlovich', '1950-01-23', 'Kemerovo', null, null, null);
        $this->create(4, 0, 3, 'Danshina', 'Tatyana', 'Ivanovna', '1952-09-17', 'Kemerovo', '2021-08-21', 'Kemerovo', null);
        $this->create(5, 0, 2, 'Danshin', 'Maxim', 'Leonidovich', '1979-11-18', 'Kemerovo', null, null, 'fakenote');
        $this->create(6, 0, 3, 'Burkina', 'Natalia', 'Vladimirovna', '1988-01-18', 'Kemerovo', null, null, null);
        $this->create(7, 0, 2, 'Danshin', 'Denis', 'Maksimovich', null, null, null, null, null);
        $this->create(8, 0, 2, 'Danshin', 'Egor', 'Leonidovich', '1981-04-13', 'Kemerovo', null, null, null);
        $this->create(9, 0, 3, 'Solovyova', 'Oksana', 'Leonidovna', '1981-04-13', 'Kemerovo', null, null, null);
        $this->create(10, 0, 2, 'Solovyov', 'Igor', 'Ivanovich', '1964-08-21', 'Kemerovo', null, null, null);
        $this->create(11, 0, 3, 'Solovyova', 'Olga', 'Igorevna', '2006-09-11', 'Kemerovo', null, null, null);
        $this->create(12, 0, 3, 'Solovyov', 'Oleg', 'Igorevich', '2012-08-22', 'Kemerovo', null, null, null);
        $this->create(13, 0, 3, 'Petrenko', 'Elena', 'Sergeevna', null, 'Moskva', null, null, null);
        $this->create(14, 0, 3, 'Petrenko', 'Nina', 'Sergeevna', '1982-08-23', 'Moskva', null, null, null);
        $this->create(15, 0, 3, 'Petrenko', 'Olga', 'Sergeevna', '1984-11-09', 'Moskva', null, null, null);
        $this->create(16, 0, 2, 'Sidorov', 'Maxim', 'Petrovich', '1999-08-21', 'Kemerovo', '2020-08-22', null, null);
        $this->create(17, 0, 2, 'Sidorov', 'Denis', 'Petrovich', '2000-08-22', 'Kemerovo', '2005-08-23', null, null);
        $this->create(18, 0, 2, 'Sidorov', 'Igor', 'Petrovich', '2002-08-24', 'Kemerovo', null, null, null);
        $this->create(19, 1, 2, null, null, 'Petrovich', null, 'Kemerovo', null, null, null);
        $this->create(20, 0, 3, null, null, null, null, 'Kemerovo', null, null, null);
        $this->create(21, 0, 3, null, 'Inna', null, null, 'Kemerovo', null, null, null);
        $this->create(22, 1, 1, 'Fakefake', 'Egor', '', '????-05-01', 'Kemerovo', null, null, null);
    }

    private function create(
        int $id,
        int $isUnavailable,
        int $gender,
        ?string $surname,
        ?string $name,
        ?string $patronymic,
        ?string $birthDate,
        ?string $birthPlace,
        ?string $deathDate,
        ?string $burialPlace,
        ?string $note
    ): void {
        $obj = new People;
        $obj->id = $id;
        $obj->is_unavailable = $isUnavailable;
        $obj->gender_id = $gender;
        $obj->surname = $surname;
        $obj->name = $name;
        $obj->patronymic = $patronymic;
        $obj->birth_date = $birthDate;
        $obj->birth_place = $birthPlace;
        $obj->death_date = $deathDate;
        $obj->burial_place = $burialPlace;
        $obj->note = $note;
        $obj->save();
    }
}
