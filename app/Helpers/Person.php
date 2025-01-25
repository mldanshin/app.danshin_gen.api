<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

final class Person
{
    public static function surname(?string $value): string
    {
        if (empty($value)) {
            return __('person.surname.null'); /* @phpstan-ignore-line */
        } else {
            return $value;
        }
    }

    /**
     * @param  Collection<int, string>|null  $collection
     */
    public static function oldSurname(?Collection $collection): string
    {
        if ($collection !== null && $collection->count() > 0) {
            return '('.implode(',', $collection->all()).')';
        } else {
            return '';
        }
    }

    public static function name(?string $value): string
    {
        if (empty($value)) {
            return __('person.name.null'); /* @phpstan-ignore-line */
        } else {
            return $value;
        }
    }

    public static function patronymic(?string $value): string
    {
        if ($value === null) {
            return __('person.patronymic.null'); /* @phpstan-ignore-line */
        } elseif ($value === '') {
            return '';
        } else {
            return $value;
        }
    }

    public static function patronymicEdit(?string $value): string
    {
        if ($value === null) {
            return '';
        } elseif ($value === '') {
            return '!';
        } else {
            return $value;
        }
    }
}
