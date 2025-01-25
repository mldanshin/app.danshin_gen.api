<?php

namespace App\Models\Dates;

use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'datesEvents',
    title: 'Список ближайших событий.',
    required: [
        'pastBirth',
        'pastBirthWould',
        'pastDeath',
        'todayBirth',
        'todayBirthWould',
        'todayDeath',
        'nearestBirth',
        'nearestBirthWould',
        'nearestDeath',
        'isEmpty',
    ]
)]
final readonly class Events
{
    #[OA\Property(
        description: 'Отсутствуют ли события.'
    )]
    public bool $isEmpty;

    /**
     * @param  Collection<int, Birth>  $pastBirth
     * @param  Collection<int, BirthWould>  $pastBirthWould
     * @param  Collection<int, Death>  $pastDeath
     * @param  Collection<int, Birth>  $todayBirth
     * @param  Collection<int, BirthWould>  $todayBirthWould
     * @param  Collection<int, Death>  $todayDeath
     * @param  Collection<int, Birth>  $nearestBirth
     * @param  Collection<int, BirthWould>  $nearestBirthWould
     * @param  Collection<int, Death>  $nearestDeath
     */
    public function __construct(
        #[OA\Property(
            description: 'Прошедшие дни рождения живых лиц.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/datesBirth'
            )
        )]
        public Collection $pastBirth,

        #[OA\Property(
            description: 'Прошедшие дни рождения умерших лиц.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/datesBirthWould'
            )
        )]
        public Collection $pastBirthWould,

        #[OA\Property(
            description: 'Прошедшие дни памяти умерших лиц.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/datesDeath'
            )
        )]
        public Collection $pastDeath,

        #[OA\Property(
            description: 'Текущие дни рождения живых лиц.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/datesBirth'
            )
        )]
        public Collection $todayBirth,

        #[OA\Property(
            description: 'Текущие дни рождения умерших лиц.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/datesBirthWould'
            )
        )]
        public Collection $todayBirthWould,

        #[OA\Property(
            description: 'Текущие дни памяти умерших лиц.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/datesDeath'
            )
        )]
        public Collection $todayDeath,

        #[OA\Property(
            description: 'Будующие дни рождения живых лиц.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/datesBirth'
            )
        )]
        public Collection $nearestBirth,

        #[OA\Property(
            description: 'Будующие дни рождения умерших лиц.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/datesBirthWould'
            )
        )]
        public Collection $nearestBirthWould,

        #[OA\Property(
            description: 'Будущие дни памяти умерших лиц.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/datesDeath'
            )
        )]
        public Collection $nearestDeath
    ) {
        $this->initializeIsEmpty();
    }

    private function initializeIsEmpty(): void
    {
        if ($this->pastBirth->count() === 0
            && $this->pastBirthWould->count() === 0
            && $this->pastDeath->count() === 0
            && $this->todayBirth->count() === 0
            && $this->todayBirthWould->count() === 0
            && $this->todayDeath->count() === 0
            && $this->nearestBirth->count() === 0
            && $this->nearestBirthWould->count() === 0
            && $this->nearestDeath->count() === 0
        ) {
            $this->isEmpty = true;
        } else {
            $this->isEmpty = false;
        }
    }
}
