<?php

namespace App\Models\Person\Editor\Editable;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personEditorEditableOldSurname',
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
