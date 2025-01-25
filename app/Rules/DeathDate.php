<?php

namespace App\Rules;

use App\Models\Date as DateModel;
use App\Models\DateTimeCustom as DateTimeCustomModel;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

final class DeathDate implements DataAwareRule, ValidationRule
{
    /**
     * @var array<string, mixed>
     */
    protected $data = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value !== null) {
            if (DateModel::checkText($value)) {
                $dateDeath = DateModel::decode($value);
                $dateCurrent = new DateTimeCustomModel;

                if (! $dateDeath->hasUnknown) {
                    if ($dateDeath->string > $dateCurrent->format('Y-m-d')) {
                        $fail('validation_app.death_date.future')->translate();
                    }

                    if (isset($this->data['birth_date'])) {
                        $dateBirth = DateModel::decode($this->data['birth_date']);
                        if (! $dateBirth->hasUnknown) {
                            if ($dateDeath->string < $dateBirth->string) {
                                $fail('validation_app.death_date.birth')->translate();
                            }
                        }
                    }
                }
            } else {
                $fail('validation_app.date.format')->translate(['attribute' => $attribute]);
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
