<?php

namespace App\Models\Person\Editor;

use App\Models\Person\Editor\PersonShort as PersonShortModel;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personEditorMarriagePossible',
    title: 'Возможные вторые половины и их роль.',
    required: [
        'role',
        'people',
    ]
)]
final readonly class MarriagePossible
{
    /**
     * @param  Collection<int, PersonShortModel>  $people
     */
    public function __construct(
        #[OA\Property(
            description: 'Роль второй половины',
        )]
        public int $role,

        #[OA\Property(
            description: 'Допустимые вторые половины.',
            type: 'array',
            items: new OA\Items(
                type: 'object',
                ref: '#/components/schemas/personEditorPersonShort'
            )
        )]
        public Collection $people
    ) {}
}
