<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class Phone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value !== null) {
            if (preg_match("/^((8|\+7)[\- ]?)?(\d{3}[\- ]?)?[\d\- ]{7,10}$/", $value) == 0) {
                $fail('validation_app.phone.format')->translate();
            }

            $phone = str_replace(['-', ' '], '', $value);
            $phone = substr($phone, -10, 10);
            if (strlen($phone) !== 10) {
                $fail('validation_app.phone.count')->translate();
            }
        }
    }
}
