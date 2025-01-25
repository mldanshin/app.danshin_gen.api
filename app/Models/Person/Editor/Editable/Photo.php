<?php

namespace App\Models\Person\Editor\Editable;

use App\Models\Date;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personEditorEditablePhoto',
    title: 'Интернет ресурсы.',
    required: [
        'order',
        'fileName',
    ]
)]
final readonly class Photo
{
    public function __construct(
        #[OA\Property(
            description: 'Порядковый номер.',
        )]
        public int $order,

        #[OA\Property(
            description: 'Имя файла.',
        )]
        public string $fileName,

        #[OA\Property(
            description: 'Дата снимка.',
            ref: '#/components/schemas/date'
        )]
        public ?Date $date
    ) {}
}
