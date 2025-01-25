<?php

namespace App\Models\Tree;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'treeTree',
    title: 'Древо лица.',
    required: [
        'personTarget',
        'family',
    ]
)]
final readonly class Tree
{
    public function __construct(
        #[OA\Property(
            description: 'Описание лица, в отношении которого построено древо.',
            ref: '#/components/schemas/treePersonShort'
        )]
        public PersonShort $personTarget,

        #[OA\Property(
            description: 'Древо его семьи.',
            ref: '#/components/schemas/treeFamily'
        )]
        public Family $family
    ) {}
}
