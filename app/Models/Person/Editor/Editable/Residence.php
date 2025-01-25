<?php

namespace App\Models\Person\Editor\Editable;

use App\Models\Date;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personEditorEditableResidence',
    title: 'Место проживания.',
    required: [
        'name',
    ]
)]
final readonly class Residence
{
    public function __construct(
        #[OA\Property(
            description: 'Наименование.',
        )]
        public string $name,

        #[OA\Property(
            description: 'Дата актуальности данных.',
            ref: '#/components/schemas/date'
        )]
        public ?Date $date
    ) {}
}
