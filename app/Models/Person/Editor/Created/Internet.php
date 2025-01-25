<?php

namespace App\Models\Person\Editor\Created;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'personEditorCreatedInternet',
    title: 'Интернет ресурсы.',
    required: [
        'url',
        'name',
    ]
)]
final readonly class Internet
{
    public function __construct(
        public string $url,
        public string $name
    ) {}
}
