<?php

namespace App\Repositories\Download\Person;

use App\Models\Download\People\Person as PersonModel;

abstract class BuilderAbstract
{
    /**
     * returns the path to the created file
     */
    abstract public function create(string $pathDirectory, PersonModel $person): string;
}
