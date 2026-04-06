<?php

namespace App\Transformers;

use App\Models\Act;
use App\Models\ActMetaMember;
use App\Models\ActMetaNote;
use App\Models\ActMetaTrait;
use App\Models\GoldenBuzzer;
use App\Models\StageWinner;
use Illuminate\Support\Str;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Primitive;
use League\Fractal\TransformerAbstract;

class ActTransformer extends TransformerAbstract
{
    protected array $availableIncludes = ['genres', 'meta', 'profile', 'profileContent', 'accolades'];

    public function transform(Act $act): array
    {
        return [
            'id'          => (int)$act->id,
            'name'        => $act->name,
            'subtitle'    => $act->subtitle,
            'slug'        => $act->slug,
            'has_profile' => (bool)$act->profile,
            'image'       => $act->image,
            'rank'        => $act->rank,
            'rank_text'   => $act->rank_text,
        ];
    }

    public function includeProfile(Act $act): ?Item
    {
        return $act->profile ? $this->item($act->profile, new ActProfileTransformer) : null;
    }

    /**
     * Include an Act's profile for display on the main site.
     */
    public function includeProfileContent(Act $act): ?Primitive
    {
        if ($act->profile)
        {
            return $this->primitive([
                'description' => Str::markdown($act->profile->description),
            ]);
        }

        return null;
    }

    public function includeGenres(Act $act): Primitive
    {
        return $this->primitive($act->genres()->pluck('name'));
    }

    public function includeMeta(Act $act): Primitive
    {
        return $this->primitive([
            'is_fan_favourite' => $act->is_fan_favourite,
            'genres'           => $act->genres()->pluck('name')->toArray(),
            'languages'        => $act->languages()->pluck('code')->toArray(),
            'members'          => $act->members->map(fn(ActMetaMember $member) => [
                'id'   => $member->id,
                'name' => $member->name,
                'role' => $member->role,
            ]),
            'traits'           => $act->traits->map(fn(ActMetaTrait $trait) => [
                'id'    => $trait->id,
                'trait' => $trait->trait,
            ]),
            'notes'            => $act->notes->map(fn(ActMetaNote $note) => [
                'id'   => $note->id,
                'note' => $note->note,
            ]),
        ]);
    }

    public function includeAccolades(Act $act): Primitive
    {
        $buzzers = $act->goldenBuzzers->map(fn(GoldenBuzzer $buzzer) => $buzzer->round->full_title)->unique();
        $wins    = $act->accolades()->get()->map(fn(StageWinner $winner) => [
            'is_winner' => $winner->is_winner,
            'text'      => $winner->is_winner ? "Winner {$winner->round->full_title}" : "{$winner->stage->title} Runner-Up",
        ]);

        return $this->primitive([
            'buzzers' => $buzzers->toArray(),
            'wins'    => $wins->toArray(),
        ]);
    }
}
