<?php

namespace App\Http\Requests\Device;

use App\Traits\BaseRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class EditDeviceRequest extends FormRequest
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
            'storage_measure' => 'in:GB,TB',
            'storage' => 'integer|min:1',
            'quantity' => 'integer|min:0'
        ];
    }
}
