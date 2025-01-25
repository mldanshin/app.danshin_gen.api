<?php

namespace App\Http\Requests\Download;

use App\Http\Requests\Request;

final class TreeRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|numeric|integer',
        ];
    }
}
