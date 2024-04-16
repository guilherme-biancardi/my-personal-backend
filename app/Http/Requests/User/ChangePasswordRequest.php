<?php

namespace App\Http\Requests\User;

use App\Traits\BaseRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'current_password' => 'required|string|min:6|',
            'password' => 'required|string|min:6|confirmed'
        ];
    }
}
