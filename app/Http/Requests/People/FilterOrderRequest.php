<?php

namespace App\Http\Requests\People;

use App\Http\Requests\Request;
use App\Models\People\FilterOrder\OrderType;
use App\Models\People\Request as PeopleRequest;
use Illuminate\Validation\Rules\Enum;

final class FilterOrderRequest extends Request
{
    private PeopleRequest $peopleRequest;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'order' => [
                'nullable',
                'string',
                new Enum(OrderType::class),
            ],
            'search' => 'nullable|string',
        ];
    }

    public function getPeopleRequest(): PeopleRequest
    {
        if (empty($this->peopleRequest)) {
            $this->peopleRequest = new PeopleRequest($this->order, $this->search);
        }

        return $this->peopleRequest;
    }
}
