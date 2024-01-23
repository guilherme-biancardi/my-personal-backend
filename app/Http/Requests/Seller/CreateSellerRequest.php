<?php

namespace App\Http\Requests\Seller;

use App\Traits\BaseRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class CreateSellerRequest extends FormRequest
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
            'name' => 'bail|required|string|max:25',
            'phone_number' => 'bail|required|celular_com_ddd',
            'cpf' => 'bail|required|formato_cpf|unique:sellers'
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number' => [
                'celular_com_ddd' => __('messages.seller.phone_invalid')
            ],
            'cpf' => [
                'unique' => __('messages.seller.cpf_exists')
            ]
        ];
    }
}
