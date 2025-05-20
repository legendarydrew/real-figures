<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * VoteRequest
 * For casting a vote, consisting of three songs in a specific round.
 *
 * @package App\Http\Requests
 */
class VoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'round_id'         => ['required', 'exists:rounds,id'],
            'first_choice_id'  => ['required', 'exists:songs,id'],
            'second_choice_id' => ['required', 'exists:songs,id', 'different:first_choice_id'],
            'third_choice_id'  => ['required', 'exists:songs,id', 'different:first_choice_id', 'different:second_choice_id'],
        ];
    }
}
