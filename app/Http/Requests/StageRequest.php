<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule>
     */
    public function rules(): array
    {
        return [
            'title'               => ['required', 'string',
                Rule::unique('stages', 'title')->ignore($this->id)
            ],
            'description'         => ['required', 'string'],
            'golden_buzzer_perks' => ['nullable', 'string'],
        ];
    }
}
