<?php

namespace App\Models\Person\Editor\Editable;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personEditorEditableParentModel',
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
            description: 'Id лица.',
        )]
        public int $person,

        #[OA\Property(
            description: 'Id роли.',
        )]
        public int $role
    ) {}
}
