<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConversationRequest extends FormRequest
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
        $userId = auth()->id();

        return [
            'type' => ['required', 'in:private,group'],

            'user_id' => [
                Rule::requiredIf($this->type === 'private'),
                'exists:users,id',
                'different:'.$userId,
            ],

            'users' => [
                Rule::requiredIf($this->type === 'group'),
                'array',
                'min:1',
                'max:20',
            ],

            'users.*' => [
                'exists:users,id',
                'different:'.$userId,
            ],

            'name' => [
                Rule::requiredIf($this->type === 'group'),
                'string',
                'min:2',
                'max:100',
            ],

            'avatar' => ['nullable', 'image', 'max:2048'],

            'description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
