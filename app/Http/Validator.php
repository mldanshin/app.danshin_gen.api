<?php

namespace App\Http;

final class Validator
{
    public static function requireIntegerOrNull(?string $id): bool
    {
        if ($id != null && ! is_numeric($id)) {
            return false;
        } else {
            return true;
        }
    }

    public static function requireInteger(string $id): bool
    {
        if (! is_numeric($id)) {
            return false;
        } else {
            return true;
        }
    }
}
