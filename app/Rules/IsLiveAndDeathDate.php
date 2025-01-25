<?php

namespace App\Rules;

use App\Models\Date as DateModel;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

final class IsLiveAndDeathDate implements DataAwareRule, ValidationRule
{
    /**
     * @var array<string, mixed>
     */
    protected $data = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (isset($this->data['death_date'])) {
            if (DateModel::checkText($this->data['death_date'])) {
                $deathDate = DateModel::decode($this->data['death_date']);

                if ($value == true && ! $deathDate->isEmpty) {
                    $fail('validation_app.is_live.death_date')->translate();
                }
            }
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
