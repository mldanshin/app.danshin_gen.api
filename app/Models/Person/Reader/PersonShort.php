<?php

namespace App\Models\Person\Reader;

use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personReaderPersonShort',
    title: 'Родитель.',
    required: [
        'person',
        'role',
    ]
)]
final readonly class PersonShort
{
    /**
     * @param  Collection<int, string>|null  $oldSurname
     */
    public function __construct(
        #[OA\Property(
            description: 'Id.',
        )]
        public int $id,

        #[OA\Property(
            description: 'Фамилия.',
        )]
        public ?string $surname,

        #[OA\Property(
            description: 'Прежние фамилии.',
            type: 'array',
            items: new OA\Items(
                type: 'string'
            )
        )]
        public ?Collection $oldSurname,

        #[OA\Property(
            description: 'Имя.',
        )]
        public ?string $name,

        #[OA\Property(
            description: 'Отчество.',
        )]
        public ?string $patronymic
    ) {}
}
