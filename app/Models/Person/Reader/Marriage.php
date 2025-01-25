<?php

namespace App\Models\Person\Reader;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personReaderMarriage',
    title: 'Брак (совместное проживание).',
    required: [
        'soulmate',
        'role',
    ]
)]
final readonly class Marriage
{
    public function __construct(
        #[OA\Property(
            description: 'Описание второго лица.',
            ref: '#/components/schemas/personReaderPersonShort'
        )]
        public PersonShort $soulmate,

        #[OA\Property(
            description: 'Id роли второго лица.',
        )]
        public int $role
    ) {}
}
