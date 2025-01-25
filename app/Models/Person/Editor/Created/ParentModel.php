<?php

namespace App\Models\Person\Editor\Created;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personEditorCreatedParentModel',
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
            description: 'Id лица',
        )]
        public int $person,

        #[OA\Property(
            description: 'Id роли.',
        )]
        public int $role
    ) {}
}
