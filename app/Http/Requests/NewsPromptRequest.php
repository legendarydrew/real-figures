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
            'references'   => ['nullable', 'array'],
            'references.*' => ['int', 'min:1'],
            'previous'     => ['nullable', 'int', 'exists:news_posts,id'],
            'prompt'       => ['nullable', 'string']
        ];
    }
}
