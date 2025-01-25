<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

final class Patronymic implements DataAwareRule, ValidationRule
{
    /**
     * @var array<string, mixed>
     */
    protected $data = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (isset($this->data['has_patronymic']) &&
            $this->data['has_patronymic'] == false &&
            ! empty($this->data['patronymic'])
        ) {
            $fail('validation_app.patronymic.no')->translate();
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
