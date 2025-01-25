<?php

namespace App\Models\Tree;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'treePersonShort',
    title: 'Описание лица.',
    required: [
        'id',
    ]
)]
final readonly class PersonShort
{
    public function __construct(
        #[OA\Property(
            description: 'Id лица.',
        )]
        public int $id,

        #[OA\Property(
            description: 'Фамилия.',
        )]
        public ?string $surname,

        #[OA\Property(
            description: 'Имя.',
        )]
        public ?string $name,

        #[OA\Property(
            description: 'Отчество.',
        )]
        public ?string $patronymic,
    ) {}
}
