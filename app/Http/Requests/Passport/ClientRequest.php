<?php

namespace App\Http\Requests\Passport;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'redirect' => 'required|array',
            'redirect.*' => 'required|url',
            'confidential' => 'nullable|boolean',
            'device_flow' => 'nullable|boolean',
        ];
    }
}
