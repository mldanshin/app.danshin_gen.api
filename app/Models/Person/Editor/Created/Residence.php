<?php

namespace App\Models\Person\Editor\Created;

use App\Models\Date;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personEditorCreatedResidence',
    title: 'Место проживания.',
    required: [
        'name',
    ]
)]
final readonly class Residence
{
    public function __construct(
        #[OA\Property(
            description: 'Описание.',
        )]
        public string $name,

        #[OA\Property(
            description: 'Дата.',
            ref: '#/components/schemas/date'
        )]
        public ?Date $date
    ) {}
}
