<?php

namespace App\Models\Person\Reader;

use App\Models\Date;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personReaderResidence',
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
