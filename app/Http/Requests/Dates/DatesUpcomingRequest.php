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
            'past_day' => 'required|numeric|integer',
            'nearest_day' => 'required|numeric|integer',
        ];
    }
}
