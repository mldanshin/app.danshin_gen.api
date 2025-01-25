<?php

namespace App\Http\Requests\Dates;

use App\Http\Requests\Request;

final class DatesUpcomingRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'before_day' => 'required|numeric|integer',
            'after_day' => 'required|numeric|integer',
        ];
    }
}
