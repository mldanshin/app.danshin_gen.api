<?php

namespace App\Models\Tree;

use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'treeToggle',
    title: 'Переключатель для древа (выбор родителя по которому строится древо).',
    required: [
        'personTarget',
        'parentList',
    ]
)]
final readonly class Toggle
{
    /**
     * @param  Collection<int, PersonShort>  $parentList
     */
    public function __construct(
        #[OA\Property(
            description: 'Описание лица, для которого создан переключатель древа.',
            ref: '#/components/schemas/treePersonShort',
        )]
        public PersonShort $personTarget,

        #[OA\Property(
            description: 'Список родителей.',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/treePersonShort',
            )
        )]
        public Collection $parentList,

        #[OA\Property(
            description: 'Id родителя, по которому строится древо.',
        )]
        public ?int $parentTarget
    ) {}
}
