<?php

namespace App\Models\Backup;

final readonly class PhotoFile
{
    public function __construct(
        public string $path,
        public string $entryName
    ) {}
}
