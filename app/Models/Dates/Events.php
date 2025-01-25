<?php

namespace App\Models\Dates;

use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'datesEvents',
    title: 'Список ближайших событий.',
    required: [
        'beforeBirth',
        'beforeBirthWould',
        'beforeDeath',
        'todayBirth',
        'todayBirthWould',
        'todayDeath',
        'afterBirth',
        'afterBirthWould',
        'afterDeath',
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
     * @param  Collection<int, Birth>  $beforeBirth
     * @param  Collection<int, BirthWould>  $beforeBirthWould
     * @param  Collection<int, Death>  $beforeDeath
     * @param  Collection<int, Birth>  $todayBirth
     * @param  Collection<int, BirthWould>  $todayBirthWould
     * @param  Collection<int, Death>  $todayDeath
     * @param  Collection<int, Birth>  $afterBirth
     * @param  Collection<int, BirthWould>  $afterBirthWould
     * @param  Collection<int, Death>  $afterDeath
     */
    public function __construct(
        #[OA\Property(
            description: 'Прошедшие дни рождения живых лиц.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/datesBirth'
            )
        )]
        public Collection $beforeBirth,

        #[OA\Property(
            description: 'Прошедшие дни рождения умерших лиц.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/datesBirthWould'
            )
        )]
        public Collection $beforeBirthWould,

        #[OA\Property(
            description: 'Прошедшие дни памяти умерших лиц.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/datesDeath'
            )
        )]
        public Collection $beforeDeath,

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
        public Collection $afterBirth,

        #[OA\Property(
            description: 'Будующие дни рождения умерших лиц.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/datesBirthWould'
            )
        )]
        public Collection $afterBirthWould,

        #[OA\Property(
            description: 'Будущие дни памяти умерших лиц.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/datesDeath'
            )
        )]
        public Collection $afterDeath
    ) {
        $this->initializeIsEmpty();
    }

    private function initializeIsEmpty(): void
    {
        if ($this->afterBirth->count() === 0
            && $this->afterBirthWould->count() === 0
            && $this->afterDeath->count() === 0
            && $this->todayBirth->count() === 0
            && $this->todayBirthWould->count() === 0
            && $this->todayDeath->count() === 0
            && $this->beforeBirth->count() === 0
            && $this->beforeBirthWould->count() === 0
            && $this->beforeDeath->count() === 0
        ) {
            $this->isEmpty = true;
        } else {
            $this->isEmpty = false;
        }
    }
}
