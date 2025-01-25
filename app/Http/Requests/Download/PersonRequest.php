<?php

namespace App\Http\Requests\Download;

use App\Http\Requests\Request;

final class PersonRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|string',
        ];
    }
}
