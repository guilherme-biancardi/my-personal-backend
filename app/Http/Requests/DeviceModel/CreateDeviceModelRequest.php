<?php

namespace App\Http\Requests\DeviceModel;

use App\Traits\BaseRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class CreateDeviceModelRequest extends FormRequest
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
            'model' => 'required|string|unique:device_models',
            'brand' => 'required|string'
        ];
    }
}
