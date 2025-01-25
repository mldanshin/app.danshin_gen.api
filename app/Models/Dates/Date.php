<?php

namespace App\Models\Dates;

use App\Models\Date as ModelsDate;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'datesDate',
    title: 'Дата события.',
    required: [
        'date',
        'type',
        'person',
    ]
)]
final readonly class Date extends Event
{
    public function __construct(
        #[OA\Property(
            description: 'Описание даты.',
            ref: '#/components/schemas/date'
        )]
        ModelsDate $date,

        #[OA\Property(
            description: 'Тип даты.',
            enum: [
                DateType::Birth,
                DateType::Death,
            ]
        )]
        public DateType $type,

        #[OA\Property(
            description: 'Лицо, связанное с событием.',
            ref: '#/components/schemas/datesPerson'
        )]
        Person $person
    ) {
        parent::__construct($date, $person);
    }
}
