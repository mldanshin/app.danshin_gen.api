<?php

namespace App\Exceptions;

use Exception;

final class ServerException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
