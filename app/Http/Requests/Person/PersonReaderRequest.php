<?php

namespace App\Http\Requests\Person;

use App\Http\Requests\Request;

final class PersonReaderRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'nullable|date',
        ];
    }
}
