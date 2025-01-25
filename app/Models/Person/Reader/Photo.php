<?php

namespace App\Models\Person\Reader;

use App\Models\Date;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personReaderPhoto',
    title: 'Описание фотоснимка',
    required: [
        'order',
        'fileName',
    ]
)]
final readonly class Photo
{
    public function __construct(
        #[OA\Property(
            description: 'Порядковый номер фотоснимка.',
        )]
        public int $order,

        #[OA\Property(
            description: 'Имя файла.',
        )]
        public string $fileName,

        #[OA\Property(
            description: 'Дата.',
        )]
        public ?Date $date
    ) {}
}
