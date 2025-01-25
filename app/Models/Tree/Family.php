<?php

namespace App\Models\Tree;

use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'treeFamily',
    title: 'Семья.',
    required: [
        'person',
        'marriage',
        'children',
    ]
)]
final readonly class Family
{
    /**
     * @param  Collection<int, Person>  $marriage
     * @param  Collection<int, Family>  $children
     */
    public function __construct(
        #[OA\Property(
            description: 'Описание лица.',
            ref: '#/components/schemas/treePerson',
        )]
        public Person $person,

        #[OA\Property(
            description: 'Брак (совместное проживание).',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/treePerson',
            )
        )]
        public Collection $marriage,

        #[OA\Property(
            description: 'Дети.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/treeFamily',
            )
        )]
        public Collection $children
    ) {}
}
