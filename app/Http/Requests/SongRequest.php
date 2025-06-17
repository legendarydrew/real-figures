<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SongRequest extends FormRequest
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
            'act_id'   => ['required', 'exists:acts,id'],
            'title'    => ['required', 'string'],
            'language' => ['required', 'string', 'min:2', 'max:2', 'exists:languages,code'],
            'url'      => ['nullable', 'string', 'url'],
        ];
    }
}
