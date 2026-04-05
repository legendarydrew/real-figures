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
            'history'      => ['array'],
            'history.*'    => ['int', 'exists:news_posts,id'],
            'acts'         => ['required_if:type,' . NewsPostType::ACT->value, 'array'],
            'acts.*'       => ['exists:acts,id'],
            'stage'        => ['required_if:type,' . NewsPostType::STAGE->value, 'int', 'exists:stages,id'],
            'round'        => ['required_if:type,' . NewsPostType::ROUND->value, 'int', 'exists:rounds,id'],
        ];
    }
}
