<?php

namespace App\Models\Download\Photo;

final readonly class FileArchive
{
    public function __construct(
        public string $path,
        public string $entryName
    ) {}
}
