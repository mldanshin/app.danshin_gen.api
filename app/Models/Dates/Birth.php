<?php

namespace App\Models\Dates;

use App\Models\Date;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'datesBirth',
    title: 'День рождения.',
    required: [
        'date',
        'person',
        'age',
    ]
)]
final readonly class Birth extends Event
{
    public function __construct(
        #[OA\Property(
            description: 'Дата.',
            ref: '#/components/schemas/date'
        )]
        Date $date,

        #[OA\Property(
            description: 'Лицо, связанное с датой.',
            ref: '#/components/schemas/datesPerson'
        )]
        Person $person,

        #[OA\Property(
            description: 'Возраст (объект DateInterval).',
            ref: '#/components/schemas/dateInterval'
        )]
        public \DateInterval $age
    ) {
        parent::__construct($date, $person);
    }
}
