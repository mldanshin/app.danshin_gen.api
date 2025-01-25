<?php

namespace App\Repositories\Download\People;

use App\Models\Download\People\Person as PersonModel;
use Illuminate\Support\Collection;

abstract class BuilderAbstract
{
    /**
     * returns the path to the created file
     *
     * @param  Collection<int, PersonModel>  $people
     */
    abstract public function create(string $pathDirectory, Collection $people): string;
}
