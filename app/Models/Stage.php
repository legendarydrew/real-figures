<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

class Stage extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Rounds associated with this Stage.
     *
     * @return HasMany
     */
    public function rounds(): HasMany
    {
        return $this->hasMany(Round::class);
    }

    /**
     * Returns TRUE if at least one Round in this Stage has started.
     *
     * @return bool
     */
    public function hasStarted(): bool
    {
        return $this->rounds->some(fn(Round $round) => $round->hasStarted());
    }

    /**
     * Returns TRUE if this Stage has no Rounds.
     *
     * @return bool
     */
    public function isInactive(): bool
    {
        return $this->rounds->isEmpty();
    }

    /**
     * Returns TRUE if this Stage has Rounds, but none of them have started.
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return $this->rounds->isNotEmpty() && !$this->isActive();
    }

    /**
     * Returns TRUE if at least one Round in this Stage is currently running.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return !$this->hasEnded() && $this->rounds->some(fn(Round $round) => $round->isActive());
    }

    /**
     * Returns TRUE if all Rounds in this Stage have ended.
     * (Or as the code suggests, every fn Round.)
     * This should return FALSE if there are no Rounds.
     *
     * @return bool
     */
    public function hasEnded(): bool
    {
        return $this->rounds->isNotEmpty() && $this->rounds->every(fn(Round $round) => $round->hasEnded());
    }

    /**
     * Returns TRUE if the Stage has ended, and any of the Rounds for this Stage require a "manual vote".
     * A Stage requires a manual vote when at least one of its Rounds has no votes.
     *
     * @return bool
     */
    public function requiresManualVote(): bool
    {
        return $this->hasEnded() &&
            !$this->winners()->count() &&
            $this->rounds->some(fn(Round $round) => $round->requiresManualVote());
    }

    public function outcomes(): HasManyThrough
    {
        return $this->hasManyThrough(RoundOutcome::class, Round::class);
    }

    public function winners(): HasMany
    {
        return $this->hasMany(StageWinner::class);
    }

    /**
     * Returns TRUE if the Stage has ended, and winning Songs can be chosen for the entire Stage.
     *
     * @return bool
     */
    public function canChooseWinners(): bool
    {
        return $this->hasEnded() && $this->rounds()->count() && !($this->requiresManualVote() || $this->winners()->count());
    }

    /**
     * Returns TRUE if the Stage has ended, and winning Songs were finalised.
     *
     * @return bool
     */
    public function isOver(): bool
    {
        return $this->hasEnded() && $this->winners()->count() > 0;
    }

    /**
     * Returns text representing the Stage's status.
     *
     * @return string
     */
    public function getStatusAttribute(): string
    {
        $status_key = 'inactive';

        if ($this->rounds->count() > 0)
        {
            $statuses   = [
                'judgement' => $this->canChooseWinners() || $this->requiresManualVote(),
                'ended' => $this->hasEnded() || $this->isOver(),
                'started'   => $this->hasStarted(),
                'ready'     => $this->rounds()->count()
            ];
            $status_key = array_key_first(array_filter($statuses)) ?? $status_key;
        }

        return trans('contest.stage.status')[$status_key];
    }

    /**
     * Returns the total number of votes cast in this Stage.
     *
     * @return int
     */
    public function getVoteCountAttribute(): int
    {
        return RoundVote::whereHas('round', function ($q) {
            $q->whereStageId($this->id);
        })->count();
    }
}
