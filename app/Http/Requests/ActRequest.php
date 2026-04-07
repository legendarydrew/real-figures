<?php

namespace App\Http\Requests;

use App\Enums\ActRank;
use App\Rules\IsBase64Image;
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
            'name'                => ['required', 'string'],
            'subtitle'            => ['nullable', 'string'],
            'slug'                => ['nullable', 'string',
                Rule::unique('acts', 'name')->ignore($this->id)],
            'profile'             => ['sometimes', 'array'],
            'profile.description' => ['nullable', 'string'],
            'new_image'           => ['nullable', new IsBase64Image],
            'remove_image'        => ['nullable', 'boolean'],
            'is_fan_favourite'    => ['sometimes', 'boolean'],
            'rank'                => ['required', Rule::enum(ActRank::class)],
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
