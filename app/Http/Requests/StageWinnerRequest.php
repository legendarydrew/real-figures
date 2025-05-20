<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StageWinnerRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'runners_up' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
