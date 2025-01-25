<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\Gender;
use App\Models\Eloquent\People;
use Illuminate\Database\Eloquent\Factories\Factory;

final class PeopleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = People::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $birthDate = [
            '1990-10-12',
            '1991-12-03',
            '1984-01-22',
            '1989-03-25',
            '1967-01-20',
            '1934-07-18',
            '1977-11-14',
            '1969-04-29',
        ];

        $deathDate = [
            '2010-09-13',
            '2012-11-15',
            '2018-07-04',
            '2019-05-19',
            '2020-10-24',
            '2017-02-07',
            '2020-07-03',
            '2019-09-13',
            null,
            null,
        ];

        return [
            'is_unavailable' => $this->faker->randomElement([0, 1]),
            'gender_id' => $this->faker->randomElement(Gender::pluck('id')->all()),
            'surname' => $this->faker->lastName(),
            'name' => $this->faker->firstName(),
            'patronymic' => $this->faker->firstName(),
            'birth_date' => $this->faker->randomElement($birthDate),
            'birth_place' => $this->faker->city(),
            'death_date' => $this->faker->randomElement($deathDate),
            'burial_place' => $this->faker->city(),
            'note' => $this->faker->text(),
        ];
    }
}
