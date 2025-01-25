<?php

namespace App\Exceptions;

use App\Models\Person\Editor\Error as ErrorModel;
use Exception;
use Illuminate\Support\Collection;

final class InvalidInputException extends Exception
{
    /**
     * @param  Collection<int, ErrorModel>  $errors
     */
    public function __construct(public Collection $errors) {}
}
