<?php

namespace App\Http\Requests;

use App\Rules\IsBase64Image;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActRequest extends FormRequest
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
            'name'                => ['required', 'string',
                Rule::unique('acts', 'name')->ignore($this->id)],
            'profile'             => ['sometimes', 'array'],
            'profile.description' => ['nullable', 'string'],
            'image'               => ['nullable', new IsBase64Image()],
            'is_fan_favourite'    => ['sometimes', 'boolean'],
            'meta'                => ['sometimes', 'array'],
            'meta.languages'      => ['array'],
            'meta.languages.*'    => ['required', 'string', 'min:2', 'max:2', 'exists:languages,code'],
            'meta.members'        => ['array'],
            'meta.members.*.name' => ['required', 'string'],
            'meta.members.*.role' => ['required', 'string'],
            'meta.notes'          => ['array'],
            'meta.notes.*.note'   => ['required', 'string'],
        ];
    }
}
