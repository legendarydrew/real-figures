<?php

namespace App\Models;

use Database\Factories\RoundFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Round extends Model
{
    /** @use HasFactory<RoundFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function getDates(): array
    {
        return ['starts_at', 'ends_at', 'created_at', 'updated_at'];
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function songs(): HasManyThrough
    {
        return $this->hasManyThrough(Song::class, RoundSongs::class, 'round_id', 'id', 'id', 'song_id');
    }

    public function outcomes(): HasMany
    {
        return $this->hasMany(RoundOutcome::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(RoundVote::class);
    }

    /**
     * Returns the results of this Round in ranked order.
     *
     * @return Collection|null
     */
    public function results(): ?Collection
    {
        if ($this->outcomes()->count())
        {
            return $this->outcomes()->get()
                        ->sort(fn($a, $b) => self::sortOutcomes($a, $b))
                        ->values(); // a nasty gotcha - otherwise the keys are preserved.
        }
        return null;
    }

    /**
     * A callback function for sorting RoundOutcomes.
     * The entries are ranked in descending order, by:
     * - total score;
     * - number of first choice votes;
     * - number of second choice votes.
     *
     * @param RoundOutcome $a
     * @param RoundOutcome $b
     * @return int
     */
    protected function sortOutcomes(RoundOutcome $a, RoundOutcome $b): int
    {
        $score_result         = $this->intcmp($a->score, $b->score);
        $first_choice_result  = $this->intcmp($a->first_votes, $b->first_votes);
        $second_choice_result = $this->intcmp($a->second_votes, $b->second_votes);

        if ($score_result) {
            return $score_result;
        }
        if ($first_choice_result) {
            return $first_choice_result;
        }
        return $second_choice_result;
    }

    /**
     * A convenience function for returning a value for sorting based on two integers.
     *
     * @param int $a
     * @param int $b
     * @return int
     */
    protected function intcmp(int $a, int $b): int
    {
        if ($a === $b)
        {
            return 0;
        }
        else
        {
            return $a < $b ? 1 : -1;
        }
    }
}
