<?php

namespace App\Http\Requests\User;

use App\Traits\BaseRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UploadPhotoRequest extends FormRequest
{
    use BaseRequestTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'bail|required|image|dimensions:max_width=256,max_height=256'
        ];
    }
}
