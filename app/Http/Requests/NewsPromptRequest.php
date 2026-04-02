<?php

namespace App\Http\Requests;

use App\Enums\NewsPostType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsPromptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'type'         => ['required', Rule::enum(NewsPostType::class)],
            'prompt'       => ['nullable', 'required_if:type,' . NewsPostType::GENERAL->value, 'string'], // required in some instances.
            'title'        => ['nullable', 'required_if:type,' . NewsPostType::GENERAL->value, 'string'],
            'quote'        => ['nullable', 'string'],
            'highlights'   => ['array'],
            'highlights.*' => ['string'],
        ];
    }
}
