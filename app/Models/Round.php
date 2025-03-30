<?php

namespace App\Models;

use App\Models\Scopes\HasEnded;
use App\Models\Scopes\HasStarted;
use Database\Factories\RoundFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[ScopedBy([HasStarted::class, HasEnded::class])]
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
     * Returns lists of winning and runner-up Songs, based on the results.
     *
     * @param int $runner_up_count
     * @return Collection[]|null
     */
    public function winning_songs(int $runner_up_count = 1): ?array
    {
        if (!$this->outcomes()->count())
        {
            return null;
        }

        $results     = $this->results();
        $output      = [
            'winners'    => new Collection(),
            'runners_up' => new Collection(),
        ];
        $last_result = null;

        // Build a list of winning entries.
        // There may be more than one, based on the scores and votes.
        foreach ($results as $index => $result)
        {
            if ($last_result && !$this->isSameResult($result, $last_result))
            {
                break;
            }
            $output['winners']->push($result->song);
            $last_result = $result;
        }

        // Build a list of runners-up, up to the number requested.
        $results = $results->slice($index);
        foreach ($results as $result)
        {
            if ($output['runners_up']->count() === $runner_up_count ||
                ($last_result && !$this->isSameResult($result, $last_result)))
            {
                break;
            }
            $output['runners_up']->push($result->song);
            $last_result = $result;
        }

        return $output;
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
        $score_result         = $this->int_compare($a->score, $b->score);
        $first_choice_result  = $this->int_compare($a->first_votes, $b->first_votes);
        $second_choice_result = $this->int_compare($a->second_votes, $b->second_votes);

        if ($score_result)
        {
            $result = $score_result;
        }
        elseif ($first_choice_result)
        {
            $result = $first_choice_result;
        }
        else
        {
            $result = $second_choice_result;
        }

        return $result;
    }

    /**
     * A convenience function for returning a value for sorting based on two integers.
     *
     * @param int $a
     * @param int $b
     * @return int
     */
    protected function int_compare(int $a, int $b): int
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

    protected function isSameResult(RoundOutcome $a, RoundOutcome $b): bool
    {
        return ($a->score === $b->score) &&
            ($a->first_votes === $b->first_votes) &&
            ($a->second_votes === $b->second_votes) &&
            ($a->third_votes === $b->third_votes);
    }


    /**
     * Returns TRUE if the Round has started.
     *
     * @return bool
     */
    public function hasStarted(): bool
    {
        return $this->starts_at < now();
    }

    /**
     * Returns TRUE if the Round has ended.
     *
     * @return bool
     */
    public function hasEnded(): bool
    {
        return $this->ends_at < now();
    }
}
