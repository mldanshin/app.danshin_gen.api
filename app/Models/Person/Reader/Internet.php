<?php

namespace App\Models\Person\Reader;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personReaderInternet',
    title: 'Интернет ресурсы.',
    required: [
        'url',
        'name',
    ]
)]
final readonly class Internet
{
    public function __construct(
        #[OA\Property(
            description: 'Url.',
        )]
        public string $url,

        #[OA\Property(
            description: 'Наименование.',
        )]
        public string $name
    ) {}
}
