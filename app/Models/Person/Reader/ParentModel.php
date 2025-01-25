<?php

namespace App\Models\Person\Reader;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personReaderParentModel',
    title: 'Родитель.',
    required: [
        'person',
        'role',
    ]
)]
final readonly class ParentModel
{
    public function __construct(
        #[OA\Property(
            description: 'Описание родителя.',
            ref: '#/components/schemas/personReaderPersonShort'
        )]
        public PersonShort $person,

        #[OA\Property(
            description: 'Id роли родителя.',
        )]
        public int $role,
    ) {}
}
