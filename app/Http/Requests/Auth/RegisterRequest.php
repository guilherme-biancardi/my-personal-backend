<?php

namespace App\Http\Requests\Auth;

use App\Traits\BaseRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    use BaseRequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'cpf' => 'required|string|cpf|formato_cpf',
            'name' => 'required|string|max:25',
            'email' => 'required|string|email|max:255|unique:users',
        ];
    }
}
