<?php

namespace App\Http\Requests\User;

use App\Traits\BaseRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'cpf' => 'required|string|cpf|formato_cpf|unique:users',
            'name' => 'required|string|max:25',
            'email' => 'required|string|email|max:255|unique:users',
        ];
    }
}
