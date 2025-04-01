<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StageRequest extends FormRequest
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
     * @return array<string, ValidationRule>
     */
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'unique:stages,title'],
            'description' => ['required', 'string'],
        ];
    }
}
