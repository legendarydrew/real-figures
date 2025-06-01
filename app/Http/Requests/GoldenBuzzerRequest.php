<?php

namespace App\Http\Requests;

class GoldenBuzzerRequest extends DonationRequest
{

    public function rules(): array
    {
        return [
            ...parent::rules(),
            'round_id' => ['required', 'exists:rounds,id'],
            'song_id'  => ['required', 'exists:songs,id'],
        ];
    }
}
