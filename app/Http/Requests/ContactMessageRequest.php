<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'body'  => ['required', 'string', 'min:20'],
            'token' => ['required', 'string', 'max:2048'],
        ];
    }
}
