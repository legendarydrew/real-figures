<?php

namespace App\Http\Requests;

class GoldenBuzzerRequest extends DonationRequest
{

    public function rules(): array
    {
        return [
            ...parent::rules(),
            'song_id' => ['required', 'exists:songs,id'],
        ];
    }
}
