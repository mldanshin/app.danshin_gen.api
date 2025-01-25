<?php

namespace App\Http\Requests\Tree;

use App\Http\Requests\Request;
use App\Models\Tree\Interactive;

final class InteractiveRequest extends Request
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
            'path_person' => 'required|string',
            'path_tree' => 'required|string',
            'image_person' => 'required|string',
            'image_tree' => 'required|string',
        ];
    }

    public function getParentId(): ?int
    {
        return (empty($this->parent_id)) ? null : $this->parent_id;
    }

    public function getModel(): Interactive
    {
        return new Interactive(
            $this->path_person,
            $this->path_tree,
            $this->image_person,
            $this->image_tree,
        );
    }
}
