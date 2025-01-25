<?php

namespace App\Http\Requests\Person;

use App\Http\Requests\Request;
use Illuminate\Http\UploadedFile;

final class PhotoStoredRequest extends Request
{
    use Phone;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'photo' => 'required|image',
        ];
    }

    public function getFile(): UploadedFile
    {
        return $this->file('photo');
    }
}
