<?php

namespace App\Transformers;

use App\Models\Donation;
use App\Models\GoldenBuzzer;
use League\Fractal\Resource\Primitive;
use League\Fractal\TransformerAbstract;

class DonationTransformer extends TransformerAbstract
{

    protected array $availableIncludes = ['amount'];

    public function transform(Donation|GoldenBuzzer $donation): array
    {
        return [
            'id'           => (int)$donation->id,
            'name'         => $donation->is_anonymous ? trans('anonymous') : $donation->name,
            'created_at'   => $donation->created_at->format(config('contest.date_format')),
            'is_anonymous' => $donation->is_anonymous,
        ];
    }

    protected function includeAmount(Donation|GoldenBuzzer $donation): Primitive
    {
        return $this->primitive(
            sprintf("%s %s", $donation->currency, number_format($donation->amount, 2)
            ));
    }
}
