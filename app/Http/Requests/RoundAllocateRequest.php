<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoundAllocateRequest extends FormRequest
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
            'song_ids'   => ['required', 'array'],
            'song_ids.*' => ['integer', 'exists:songs,id'],
            'per_round' => ['required', 'integer', 'between:2,' . config('contest.rounds.maxSongs')],
            'start_at'  => ['nullable', 'date', Rule::date()->todayOrAfter()],
            'duration'  => ['required', 'integer', 'between:1,' . config('contest.rounds.maxDuration')],
        ];
    }
}
