<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name'                => ['required', 'string',
                Rule::unique('acts', 'name')->ignore($this->id)],
            'slug'         => ['nullable', 'string'],
            'profile'             => ['sometimes', 'array'],
            'profile.description' => ['nullable', 'string'],
            'image'        => ['nullable', 'image'],
            'remove_image' => ['nullable', 'boolean'],
            'is_fan_favourite'    => ['sometimes', 'boolean'],
            'meta'                => ['sometimes', 'array'],
            'meta.genres'         => ['array'],
            'meta.genres.*'       => ['required', 'string', 'min:2'],
            'meta.languages'      => ['array'],
            'meta.languages.*'    => ['required', 'string', 'min:2', 'max:2', 'exists:languages,code'],
            'meta.members'        => ['array'],
            'meta.members.*.name' => ['required', 'string'],
            'meta.members.*.role' => ['required', 'string'],
            'meta.notes'          => ['array'],
            'meta.notes.*.note'   => ['required', 'string'],
            'meta.traits'         => ['array'],
            'meta.traits.*.trait' => ['required', 'string'],
        ];
    }
}
