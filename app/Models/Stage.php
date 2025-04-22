<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

/**
 * STAGE model
 *
 * @property int                         $id
 * @property string                      $title
 * @property string                      $description
 * @property Carbon|null                 $created_at
 * @property Carbon|null                 $updated_at
 * @property-read Collection<int, Round> $rounds
 * @property-read int|null               $rounds_count
 * @method static \Database\Factories\StageFactory factory($count = null, $state = [])
 * @method static Builder<static>|Stage newModelQuery()
 * @method static Builder<static>|Stage newQuery()
 * @method static Builder<static>|Stage query()
 * @method static Builder<static>|Stage whereCreatedAt($value)
 * @method static Builder<static>|Stage whereDescription($value)
 * @method static Builder<static>|Stage whereId($value)
 * @method static Builder<static>|Stage whereTitle($value)
 * @method static Builder<static>|Stage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
     * Returns TRUE if all Rounds in this Stage have ended.
     * (Or as the code suggests, every fn Round.)
     *
     * @return bool
     */
    public function hasEnded(): bool
    {
        return $this->rounds->every(fn(Round $round) => $round->hasEnded());
    }

    /**
     * Returns TRUE if the Stage has ended, and any of the Rounds for this Stage require a "manual vote".
     *
     * @return bool
     */
    public function requiresManualVote(): bool
    {
        return $this->hasEnded() && !$this->winners()->count() && $this->rounds->some(fn(Round $round) => $round->requiresManualVote());
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
        return $this->hasEnded() && $this->rounds()->count() && !$this->requiresManualVote();
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
                'ended'     => $this->hasEnded(),
                'started'   => $this->hasStarted(),
                'ready'     => $this->rounds()->count()
            ];
            $status_key = array_key_first(array_filter($statuses)) ?? $status_key;
        }

        return trans('contest.stage.status')[$status_key];
    }

}
