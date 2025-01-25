<?php

namespace App\Models\Person\Editor\Created;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personEditorCreatedOldSurname',
    title: 'Прежние фамилии.',
    required: [
        'surname',
        'order',
    ]
)]
final readonly class OldSurname
{
    public function __construct(
        #[OA\Property(
            description: 'Фамилия.',
        )]
        public string $surname,

        #[OA\Property(
            description: 'Порядковый номер.',
        )]
        public int $order
    ) {}
}
