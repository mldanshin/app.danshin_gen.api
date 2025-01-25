<?php

namespace App\Repositories\Download;

abstract class CreatorFile
{
    /**
     * returns the path to the created file
     */
    abstract public function create(string $pathDirectory): ?string;
}
