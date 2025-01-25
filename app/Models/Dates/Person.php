<?php

namespace App\Models\Dates;

use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'datesPerson',
    title: 'Лицо, связанное с событием.',
    required: [
        'id',
    ]
)]
final readonly class Person
{
    /**
     * @param  Collection<int, string>|null  $oldSurname
     */
    public function __construct(
        #[OA\Property(
            description: 'Id',
        )]
        public int $id,

        #[OA\Property(
            description: 'Фамилия',
        )]
        public ?string $surname,

        #[OA\Property(
            description: 'Прежнии фамилии',
            type: 'array',
            items: new OA\Items(
                type: 'string'
            )
        )]
        public ?Collection $oldSurname,

        #[OA\Property(
            description: 'Имя',
        )]
        public ?string $name,

        #[OA\Property(
            description: 'Отчество',
        )]
        public ?string $patronymic
    ) {}
}
