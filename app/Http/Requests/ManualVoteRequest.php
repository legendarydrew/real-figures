<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ManualVoteRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'votes'                   => ['required', 'array', 'min:1'],
            'votes.*.round_id'        => ['integer', 'exists:rounds,id'],
            'votes.*.song_ids'        => ['required', 'array'],
            'votes.*.song_ids.first'  => ['required', 'integer', 'exists:songs,id'],
            'votes.*.song_ids.second' => ['required', 'integer', 'exists:songs,id', 'different:votes.*.song_ids.first'],
            'votes.*.song_ids.third'  => ['required', 'integer', 'exists:songs,id', 'different:votes.*.song_ids.first', 'different:votes.*.song_ids.second'],
        ];
    }
}
