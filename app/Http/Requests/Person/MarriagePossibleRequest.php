<?php

namespace App\Http\Requests\Person;

use App\Http\Requests\Request;
use App\Models\Date as DateModel;
use App\Rules\BirthDate as BirthDateRule;
use Illuminate\Support\Collection;

final class MarriagePossibleRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'person_id' => 'nullable|integer|exists:people,id',
            'birth_date' => ['nullable', 'string', new BirthDateRule],
            'role_id' => 'required|integer|exists:marriage_roles,id',
            'parents' => 'sometimes|array',
            'parents.*.person' => [
                'required_with:parents',
                'integer',
                'distinct',
                'exists:people,id',
                'different:person_id',
            ],
        ];
    }

    public function getPersonId(): ?int
    {
        return $this->input('person_id');
    }

    public function getBirthDate(): ?DateModel
    {
        return DateModel::decode($this->input('birth_date'));
    }

    public function getRoleId(): int
    {
        return $this->input('role_id');
    }

    /**
     * @return Collection<int, int>
     */
    public function getParents(): Collection
    {
        return collect($this->input('parents'));
    }
}
