<?php

namespace App\Models\Tree;

use App\Models\Date;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'treePerson',
    title: 'Описание лица.',
    required: [
        'id',
        'isPersonTarget',
    ]
)]
final readonly class Person
{
    /**
     * @param  Collection<int, string>|null  $oldSurname
     */
    public function __construct(
        #[OA\Property(
            description: 'Id.'
        )]
        public int $id,

        #[OA\Property(
            description: 'Фамилия.'
        )]
        public ?string $surname,

        #[OA\Property(
            description: 'Прежние фамилии.',
            type: 'array',
            items: new OA\Items(
                type: 'string',
            )
        )]
        public ?Collection $oldSurname,

        #[OA\Property(
            description: 'Имя.'
        )]
        public ?string $name,

        #[OA\Property(
            description: 'Отчество.'
        )]
        public ?string $patronymic,

        #[OA\Property(
            description: 'Дата рождения.',
            ref: '#/components/schemas/date',
        )]
        public ?Date $birthDate,

        #[OA\Property(
            description: 'Дата смерти.',
            ref: '#/components/schemas/date',
        )]
        public ?Date $deathDate,

        #[OA\Property(
            description: 'Является ли лицо, лицом в отношении которого построено древо.'
        )]
        public bool $isPersonTarget
    ) {}
}
