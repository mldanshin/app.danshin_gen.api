<?php

namespace App\Models\Dates;

use App\Models\Date;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'datesDeath',
    title: 'День смерти.',
    required: [
        'date',
        'person',
        'interval',
    ]
)]
final readonly class Death extends Event
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
        public ?\DateInterval $age,

        #[OA\Property(
            description: 'Период с дня смерти (объект DateInterval).',
            ref: '#/components/schemas/dateInterval'
        )]
        public \DateInterval $interval
    ) {
        parent::__construct($date, $person);
    }
}
