<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsPostRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title'   => ['required', 'string', 'min:10', 'max:255'],
            'content' => ['required', 'string'],
            'publish' => ['sometimes', 'boolean'],
        ];
    }
}
