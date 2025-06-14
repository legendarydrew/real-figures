<?php

namespace App\Transformers;

use App\Models\Stage;
use League\Fractal\Resource\Primitive;
use League\Fractal\TransformerAbstract;

class StageAdminTransformer extends TransformerAbstract
{

    protected array $defaultIncludes = ['rounds'];

    public function transform(Stage $stage): array
    {
        return [
            'id'                  => (int)$stage->id,
            'title'               => $stage->title,
            'description'         => $stage->description,
            'golden_buzzer_perks' => $stage->golden_buzzer_perks,
            'status'              => [
                'text'           => $stage->status,
                'choose_winners' => $stage->canChooseWinners(),
                'has_started'    => $stage->hasStarted(),
                'has_ended'      => $stage->hasEnded(),
                'manual_vote'    => $stage->requiresManualVote()
            ],
            'winners'             => fractal($stage->winners()->orderByDesc('is_winner')->orderByDesc('id')->get(), new StageWinnerTransformer())
        ];
    }

    public function includeRounds(Stage $stage): Primitive
    {
        $rounds = fractal($stage->rounds)->parseIncludes(['songs'])->transformWith(new RoundAdminTransformer())->toArray();
        return $this->primitive($rounds);
    }

}
