<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 *
 *
 * @property int                             $id
 * @property int                             $round_id
 * @property int                             $song_id
 * @property int                             $first_votes
 * @property int                             $second_votes
 * @property int                             $third_votes
 * @property int                             $was_random
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int                        $score
 * @property-read int                        $vote_count
 * @property-read \App\Models\Round          $round
 * @property-read \App\Models\Song           $song
 * @property-read \App\Models\Stage|null     $stage
 * @method static \Database\Factories\RoundOutcomeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundOutcome newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundOutcome newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundOutcome query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundOutcome whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundOutcome whereFirstVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundOutcome whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundOutcome whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundOutcome whereSecondVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundOutcome whereSongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundOutcome whereThirdVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundOutcome whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundOutcome whereWasRandom($value)
 * @mixin \Eloquent
 */
class RoundOutcome extends Model
{
    /** @use HasFactory<\Database\Factories\RoundOutcomeFactory> */
    use HasFactory;

    public function stage(): HasOneThrough
    {
        return $this->hasOneThrough(Stage::class, Round::class, 'id', 'id', 'id', 'stage_id');
    }

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }

    /**
     * Returns the total score for the respective song in the respective round.
     *
     * @return int
     */
    public function getScoreAttribute(): int
    {
        $rank_points  = config('contest.points');
        $total_points = $this->first_votes * $rank_points[0];
        $total_points += $this->second_votes * $rank_points[1];
        $total_points += $this->third_votes * $rank_points[2];

        return $total_points;
    }

    /**
     * Returns the total number of votes for the respective song in the respective round.
     *
     * @return int
     */
    public function getVoteCountAttribute(): int
    {
        return $this->first_votes + $this->second_votes + $this->third_votes;
    }
}
