<?php

namespace App\Http\Requests\Tree;

use App\Http\Requests\Request;

final class TreeRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|numeric|integer',
        ];
    }

    public function getParentId(): ?int
    {
        return (empty($this->parent_id)) ? null : $this->parent_id;
    }
}
