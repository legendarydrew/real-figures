<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

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
     * Returns the total score for the respective Song in the respective Round.
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
